<#
    build.ps1 — assembles the static pages from _partials/ + _pages/

    THE SITE DOES NOT REQUIRE THIS SCRIPT TO RUN.
    Every .html file in the project root is already complete and standalone.

    Run it only when you change something shared (header, footer, mobile nav,
    the HTML skeleton) or edit a page source under _pages/. It rewrites the
    root .html files so the shared chrome stays byte-identical everywhere.

        pwsh -File build.ps1          (or:  powershell -File build.ps1)

    Page sources live in _pages/<slug>.html and begin with a front-matter
    block delimited by ---. Recognised keys:

        title        <title> and og:title
        description  meta description and og:description
        canonical    absolute canonical URL
        ogimage      filename inside assets/images/brand/
        ogalt        alt text for the share image
        ogtype       website | article   (default: website)
        nav          hem | dolda-fel | om-oss | medarbetare | artiklar | kontakt
        preload      optional raw HTML injected into <head> (LCP preloads)
        jsonld       optional path to a .json file in _pages/schema/
#>

$ErrorActionPreference = 'Stop'
$root      = Split-Path -Parent $MyInvocation.MyCommand.Path
$partials  = Join-Path $root '_partials'
$pagesDir  = Join-Path $root '_pages'

$base   = Get-Content (Join-Path $partials 'base.html')   -Raw -Encoding UTF8
$header = Get-Content (Join-Path $partials 'header.html') -Raw -Encoding UTF8
$footer = Get-Content (Join-Path $partials 'footer.html') -Raw -Encoding UTF8

$built = 0
foreach ($file in Get-ChildItem $pagesDir -Filter '*.html' -File) {
    $raw = Get-Content $file.FullName -Raw -Encoding UTF8

    # --- front matter -------------------------------------------------------
    $meta = @{}
    $body = $raw
    if ($raw -match '(?s)^\s*---\r?\n(.*?)\r?\n---\r?\n(.*)$') {
        $body = $Matches[2]
        foreach ($line in $Matches[1] -split "`r?`n") {
            if ($line -match '^\s*([a-z]+)\s*:\s*(.*)$') { $meta[$Matches[1]] = $Matches[2].Trim() }
        }
    }

    # --- optional head fragments -------------------------------------------
    $preload = ''
    if ($meta.ContainsKey('preload') -and $meta['preload']) {
        $pf = Join-Path $pagesDir ('head/' + $meta['preload'])
        if (Test-Path $pf) { $preload = (Get-Content $pf -Raw -Encoding UTF8).TrimEnd() }
    }
    $jsonld = ''
    if ($meta.ContainsKey('jsonld') -and $meta['jsonld']) {
        $jf = Join-Path $pagesDir ('schema/' + $meta['jsonld'])
        if (Test-Path $jf) { $jsonld = (Get-Content $jf -Raw -Encoding UTF8).TrimEnd() }
    }

    # --- active navigation item --------------------------------------------
    $navKey = if ($meta.ContainsKey('nav')) { $meta['nav'] } else { '' }
    $hdr = $header
    if ($navKey) {
        $hdr = $hdr -replace ('(<(?:a|button) class="nav-link" data-nav="' + [regex]::Escape($navKey) + '")'), '$1 aria-current="page"'
    }

    # --- assemble -----------------------------------------------------------
    $out = $base
    $out = $out.Replace('{{TITLE}}',       $meta['title'])
    $out = $out.Replace('{{DESCRIPTION}}', $meta['description'])
    $out = $out.Replace('{{CANONICAL}}',   $meta['canonical'])
    $out = $out.Replace('{{OGIMAGE}}',     $meta['ogimage'])
    $out = $out.Replace('{{OGALT}}',       $meta['ogalt'])
    $out = $out.Replace('{{OGTYPE}}',      $(if ($meta.ContainsKey('ogtype') -and $meta['ogtype']) { $meta['ogtype'] } else { 'website' }))
    $out = $out.Replace('{{PRELOAD}}',     $preload)
    $out = $out.Replace('{{JSONLD}}',      $jsonld)
    $out = $out.Replace('{{HEADER}}',      $hdr.TrimEnd())
    $out = $out.Replace('{{FOOTER}}',      $footer.TrimEnd())
    $out = $out.Replace('{{BODY}}',        $body.Trim())

    # collapse the blank lines left behind by empty optional fragments
    $out = $out -replace '(\r?\n){3,}', "`r`n`r`n"

    $target = Join-Path $root $file.Name
    [System.IO.File]::WriteAllText($target, $out, (New-Object System.Text.UTF8Encoding($false)))
    Write-Host ("built  {0}" -f $file.Name)
    $built++
}
Write-Host ("`n{0} page(s) built." -f $built)
