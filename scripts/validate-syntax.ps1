[CmdletBinding()]
param(
    [string]$PhpPath = 'php',
    [string]$NodePath = 'node'
)

$ErrorActionPreference = 'Stop'
$PSNativeCommandUseErrorActionPreference = $false
$repositoryRoot = Split-Path -Parent $PSScriptRoot
$applicationRoot = Join-Path $repositoryRoot 'SnowNoVA'

function Get-SyntaxFiles {
    param([string]$Directory)

    foreach ($entry in Get-ChildItem -LiteralPath $Directory -Force) {
        # Do not follow links out of the lab or inspect personal profile pages.
        if ($entry.Attributes -band [IO.FileAttributes]::ReparsePoint) { continue }
        if ($entry.PSIsContainer) {
            if ($entry.Name -notin @('resumes', 'node_modules', 'vendor', '.git')) {
                Get-SyntaxFiles -Directory $entry.FullName
            }
        } elseif ($entry.Extension -in @('.php', '.js')) {
            $entry
        }
    }
}

try {
    $php = (Get-Command -Name $PhpPath -CommandType Application -ErrorAction Stop | Select-Object -First 1).Source
    $node = (Get-Command -Name $NodePath -CommandType Application -ErrorAction Stop | Select-Object -First 1).Source
    $application = Get-Item -LiteralPath $applicationRoot
    if (!$application.PSIsContainer -or ($application.Attributes -band [IO.FileAttributes]::ReparsePoint)) {
        throw 'Application directory must be a regular directory.'
    }
    $files = @(Get-SyntaxFiles -Directory $applicationRoot | Sort-Object FullName)
    $phpCount = @($files | Where-Object Extension -eq '.php').Count
    $jsCount = @($files | Where-Object Extension -eq '.js').Count
    if ($phpCount -eq 0 -or $jsCount -eq 0) { throw 'Expected PHP and JavaScript files.' }
} catch {
    Write-Output 'SETUP FAILED: ensure SnowNoVA is readable and PHP/Node executables are available. Use -PhpPath and -NodePath if needed.'
    exit 2
}

$failures = 0
$savedNodeOptions = $env:NODE_OPTIONS
try {
    # Prevent NODE_OPTIONS from preloading code during a syntax-only check.
    $env:NODE_OPTIONS = $null
    foreach ($file in $files) {
        $relativePath = $file.FullName.Substring($repositoryRoot.Length + 1)
        try {
            # Suppress native diagnostics: parsers may echo sensitive source.
            if ($file.Extension -eq '.php') {
                & $php -n -l $file.FullName *> $null
            } else {
                & $node --check $file.FullName *> $null
            }
            $passed = $LASTEXITCODE -eq 0
        } catch {
            $passed = $false
        }
        if (!$passed) {
            $failures++
            Write-Output "FAIL: $relativePath"
        }
    }
} finally {
    $env:NODE_OPTIONS = $savedNodeOptions
}

Write-Output "Checked $phpCount PHP files and $jsCount external JavaScript files; failures: $failures."
if ($failures -gt 0) { exit 1 }
exit 0
