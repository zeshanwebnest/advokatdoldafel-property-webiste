<?php
/**
 * Template Name: FAQ — vanliga frågor
 *
 * Frequently asked questions about hidden defects.
 *
 * Built from the static page "faq.html". The copy is the client's and is fixed —
 * see README.md in the theme root before editing any of it.
 *
 * @package advantage-dolda-fel
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Preload the hero so the largest paint is requested before the CSS resolves.
adf_preload_hero(
	'assets/images/property/hero-waterside-homes-wide-1800.webp',
	'assets/images/property/hero-waterside-homes-wide-1200.webp 1200w, assets/images/property/hero-waterside-homes-wide-1800.webp 1800w, assets/images/property/hero-waterside-homes-wide-2400.webp 2400w',
	'100vw'
);

get_header();
?>

<!-- ===================== 1. HERO ===================== -->
<section class="hero hero--estate" aria-labelledby="hero-title">
  <div class="hero__frame">
    <div class="hero__media" aria-hidden="true">
      <img src="<?php echo ADF_URI; ?>/assets/images/property/hero-waterside-homes-wide-1800.webp"
           srcset="<?php echo ADF_URI; ?>/assets/images/property/hero-waterside-homes-wide-1200.webp 1200w,
                   <?php echo ADF_URI; ?>/assets/images/property/hero-waterside-homes-wide-1800.webp 1800w,
                   <?php echo ADF_URI; ?>/assets/images/property/hero-waterside-homes-wide-2400.webp 2400w"
           sizes="100vw" width="1800" height="1013"
           fetchpriority="high" decoding="async" alt="">
    </div>

    <div class="container hero__body">
      <span class="eyebrow-rule eyebrow-rule--dark" aria-hidden="true"></span>

      <h1 class="hero__title" id="hero-title">Vanliga frågor och svar om Dolda Fel</h1>
    </div>

    <figure class="hero__inset" aria-hidden="true">
      <img src="<?php echo ADF_URI; ?>/assets/images/property/oak-stair-hall-portrait-600.webp"
           srcset="<?php echo ADF_URI; ?>/assets/images/property/oak-stair-hall-portrait-600.webp 600w,
                   <?php echo ADF_URI; ?>/assets/images/property/oak-stair-hall-portrait-900.webp 900w"
           sizes="15rem" width="600" height="750"
           loading="lazy" decoding="async" alt="">
    </figure>
  </div>
</section>

<!-- ===================== 2. ADVANTAGE ADVOKATBYRÅ ===================== -->
<section class="section section--lg" aria-labelledby="intro-title">
  <div class="container">
    <div class="feature-panel feature-panel--square" data-reveal>
      <div class="feature-panel__media">
        <img src="<?php echo ADF_URI; ?>/assets/images/property/interior-stair-dining-1000.webp"
             srcset="<?php echo ADF_URI; ?>/assets/images/property/interior-stair-dining-600.webp 600w,
                     <?php echo ADF_URI; ?>/assets/images/property/interior-stair-dining-1000.webp 1000w,
                     <?php echo ADF_URI; ?>/assets/images/property/interior-stair-dining-1600.webp 1600w"
             sizes="(min-width: 940px) 46vw, 100vw"
             width="1000" height="667" loading="lazy" decoding="async"
             alt="Ett matrum i en modern bostad med trappa mot övervåningen">
      </div>

      <div class="feature-panel__body">
        <span class="eyebrow-rule" aria-hidden="true"></span>
        <h2 class="h2" id="intro-title">Advantage Advokatbyrå – Advantage Advokatbyrå</h2>
        <div class="prose u-mt-5">
          <p>Att upptäcka dolda fel i en fastighet eller bostad kan få betydande ekonomiska konsekvenser för dig som köpare. På Advantage Advokatbyrå hjälper vi både privatpersoner och företag i tvister som rör dolda fel.</p>
          <p>Vårt mål är att tillhandahålla tydlig, engagerad och resultatorienterad juridisk hjälp för att hjälpa dig att navigera i dessa komplexa frågor med tillförsikt.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===================== 3. VANLIGA FRÅGOR ===================== -->
<section class="section section--lg section--alt" aria-labelledby="faq-title">
  <div class="container">
    <div class="section-head section-head--center" data-reveal>
      <span class="eyebrow-rule" aria-hidden="true"></span>
      <h2 class="h2" id="faq-title">Vanliga frågor om tvist vid dolda fel</h2>
    </div>

    <div class="faq-wrap">
      <div class="accordion" id="faq" data-single data-reveal>
        <div class="accordion__item">
          <button class="accordion__trigger" type="button" data-open="true">
            1. Vad räknas som ett dolt fel i en fastighet?
            <span class="accordion__icon" aria-hidden="true"></span>
          </button>
          <div class="accordion__panel"><div><div class="accordion__body">
            <p>Ett dolt fel är ett fel som fanns i fastigheten eller bostaden redan vid köpet men som inte gick att upptäcka vid en noggrann undersökning/besiktning.</p>
            <p>Felet ska inte heller ha varit förväntat med hänsyn till fastighetens ålder, skick och pris. Om dessa kriterier är uppfyllda kan säljaren i vissa fall bli ansvarig enligt jordabalken.</p>
          </div></div></div>
        </div>

        <div class="accordion__item">
          <button class="accordion__trigger" type="button">
            2.Hur länge ansvarar säljaren för dolda fel?
            <span class="accordion__icon" aria-hidden="true"></span>
          </button>
          <div class="accordion__panel"><div><div class="accordion__body">
            <p>Säljaren ansvarar normalt för dolda fel i upp till tio år efter tillträdet vid fastighetsköp. Det innebär dock inte att köparen kan vänta hur länge som helst med att framställa krav.När ett fel upptäcks måste köparen reklamera felet till säljaren inom skälig tid.</p>
          </div></div></div>
        </div>

        <div class="accordion__item">
          <button class="accordion__trigger" type="button">
            3.Vad ska man göra om man upptäcker ett dolt fel?
            <span class="accordion__icon" aria-hidden="true"></span>
          </button>
          <div class="accordion__panel"><div><div class="accordion__body">
            <p><strong>Om du upptäcker ett fel efter ett fastighetsköp bör du:</strong></p>
            <ol>
              <li>Dokumentera felet noggrant</li>
              <li>Kontakta en sakkunnig eller besiktningsman</li>
              <li>Reklamera felet skriftligt till säljaren</li>
              <li>Överväga att kontakta en advokat för juridisk rådgivning</li>
            </ol>
            <p>Det är viktigt att agera relativt snabbt för att inte riskera att förlora rätten att kräva ersättning.</p>
          </div></div></div>
        </div>

        <div class="accordion__item">
          <button class="accordion__trigger" type="button">
            4.Vilken ersättning kan man få vid dolda fel?
            <span class="accordion__icon" aria-hidden="true"></span>
          </button>
          <div class="accordion__panel"><div><div class="accordion__body">
            <p>Om ett fel bedöms vara ett dolt fel kan köparen i vissa fall ha rätt till:</p>
            <ul>
              <li><strong>prisavdrag</strong>, vilket motsvarar fastighetens värdeminskning</li>
              <li><strong>skadestånd</strong>för kostnader som uppstått på grund av felet</li>
              <li><strong>hävning av köpet</strong>i särskilt allvarliga fall</li>
            </ul>
            <p>Vilken ersättning som kan bli aktuell beror på felets omfattning och omständigheterna i det enskilda fallet.</p>
          </div></div></div>
        </div>

        <div class="accordion__item">
          <button class="accordion__trigger" type="button">
            5.Vad krävs för att vinna en tvist om dolda fel?
            <span class="accordion__icon" aria-hidden="true"></span>
          </button>
          <div class="accordion__panel"><div><div class="accordion__body">
            <p><strong>För att få rätt i en tvist om dolda fel måste köparen normalt kunna visa att:</strong></p>
            <ul>
              <li>felet fanns i fastigheten vid köpet</li>
              <li>felet inte gick att upptäcka vid en noggrann undersökning, besiktning</li>
              <li>felet inte var förväntat med hänsyn till fastighetens ålder och skick</li>
            </ul>
            <p>Bevisning kan exempelvis bestå av besiktningsprotokoll, tekniska utlåtanden och annan dokumentation.</p>
          </div></div></div>
        </div>

        <div class="accordion__item">
          <button class="accordion__trigger" type="button">
            6.Måste en tvist om dolda fel gå till domstol?
            <span class="accordion__icon" aria-hidden="true"></span>
          </button>
          <div class="accordion__panel"><div><div class="accordion__body">
            <p>Nej, många tvister löses genom förhandling mellan köpare och säljare. I vissa fall kan parterna nå en förlikning utan att behöva gå till domstol. Om parterna inte kan enas kan tvisten i stället prövas i domstol.</p>
          </div></div></div>
        </div>

        <div class="accordion__item">
          <button class="accordion__trigger" type="button">
            7.När bör man kontakta en advokat vid dolda fel?
            <span class="accordion__icon" aria-hidden="true"></span>
          </button>
          <div class="accordion__panel"><div><div class="accordion__body">
            <p><strong>Det kan vara klokt att kontakta en advokat om:</strong></p>
            <ul>
              <li>du misstänker att ett fel är ett dolt fel</li>
              <li>säljaren bestrider ansvar</li>
              <li>kostnaderna för felet är betydande</li>
              <li>tvisten riskerar att gå till domstol</li>
            </ul>
            <p>En advokat kan hjälpa till att analysera situationen och föra klientens talan i förhandling eller domstol.</p>
          </div></div></div>
        </div>

        <div class="accordion__item">
          <button class="accordion__trigger" type="button">
            8.Kan man kräva ersättning även om huset är gammalt?
            <span class="accordion__icon" aria-hidden="true"></span>
          </button>
          <div class="accordion__panel"><div><div class="accordion__body">
            <p>Ja, men bedömningen påverkas av husets ålder och skick. Äldre hus kan förväntas ha vissa brister, vilket innebär att färre fel klassificeras som dolda fel. Samtidigt kan även äldre fastigheter ha fel som anses vara dolda om de är oväntade och inte gick att upptäcka vid köpet.</p>
          </div></div></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===================== 4. OM ADVANTAGE ADVOKATBYRÅ ===================== -->
<section class="section section--lg" aria-labelledby="om-title">
  <div class="container">
    <div class="split split--media-wide split--flush">
      <figure class="split__media media-figure" data-reveal>
        <img src="<?php echo ADF_URI; ?>/assets/images/property/advisory-desk-work-1000.webp"
             srcset="<?php echo ADF_URI; ?>/assets/images/property/advisory-desk-work-600.webp 600w,
                     <?php echo ADF_URI; ?>/assets/images/property/advisory-desk-work-1000.webp 1000w,
                     <?php echo ADF_URI; ?>/assets/images/property/advisory-desk-work-1600.webp 1600w"
             sizes="(min-width: 940px) 44vw, 100vw"
             width="1000" height="667" loading="lazy" decoding="async"
             alt="Två rådgivare går igenom underlaget i ett ärende">
      </figure>

      <div data-reveal data-reveal-delay="80">
        <span class="eyebrow-rule" aria-hidden="true"></span>
        <h2 class="h2" id="om-title">Om Advantage Advokatbyrå</h2>
        <div class="prose u-mt-5">
          <p>Advantage Advokatbyrå har varit verksam i över 18 år och erbjuder kvalificerad juridisk rådgivning till både privatpersoner och företag. Vi arbetar brett inom juridiken och har lång erfarenhet av att bistå våra klienter i både rådgivning och tvister.</p>
          <p>Genom vår erfarenhet och kompetens kan vi erbjuda strategisk och praktiskt juridisk rådgivning i komplexa frågor. Vi arbetar alltid med målsättningen att hitta effektiva och hållbara lösningar som tillvaratar klientens intressen på bästa möjliga sätt.</p>
        </div>
      </div>
    </div>

    <div class="prose prose--cols u-mt-8" data-reveal>
      <p>Vi har särskild erfarenhet inom flera centrala rättsområden, bland annat:</p>
      <ul>
        <li><strong>Fastighetsrätt</strong>– exempelvis frågor om dolda fel och byggfel.</li>
        <li><strong>Entreprenadrätt</strong>– tvister och rådgivning kopplade till bygg- och entreprenadprojekt</li>
        <li><strong>Affärsjuridik</strong>– juridiskt stöd till företag i kommersiella frågor och avtal</li>
        <li><strong>Tvistelösning</strong>– representation i förhandlingar, skiljeförfaranden och domstolsprocesser.</li>
      </ul>
      <p>Advantage Advokatbyrå bistår klienter i hela Sverige och arbetar ofta med ärenden som kräver både juridisk expertis och en strategisk förståelse för klientens situation. Oavsett om det gäller en juridisk rådgivning, en förhandling eller en tvist i domstol är vårt mål att erbjuda tydlig, engagerad och resultatinriktad juridisk hjälp. Utöver vår egen interna expertis har vi ett omfattande nätverk med experter på områden som ligger utanför juridiken.</p>
    </div>

    <div class="btn-row u-mt-7" data-reveal>
      <a class="btn btn--primary" href="#">
        Fråga en expert <span class="icon icon--arrow" aria-hidden="true"></span>
      </a>
    </div>
  </div>
</section>

<!-- ===================== 5. KONTAKTA OSS FÖR RÅDGIVNING ===================== -->
<section class="section section--lg cta cta--split" aria-labelledby="kontakt-title">
  <div class="cta__media" aria-hidden="true">
    <img src="<?php echo ADF_URI; ?>/assets/images/property/stockholm-skyline-wide-1600.webp"
         srcset="<?php echo ADF_URI; ?>/assets/images/property/stockholm-skyline-wide-1000.webp 1000w,
                 <?php echo ADF_URI; ?>/assets/images/property/stockholm-skyline-wide-1600.webp 1600w,
                 <?php echo ADF_URI; ?>/assets/images/property/stockholm-skyline-wide-2400.webp 2400w"
         sizes="100vw" width="1600" height="600" loading="lazy" decoding="async" alt="">
  </div>
  <div class="container cta__inner on-dark">
    <div class="cta__grid">
      <div data-reveal>
        <span class="eyebrow-rule eyebrow-rule--dark" aria-hidden="true"></span>
        <h2 class="h2" id="kontakt-title">Kontakta oss för rådgivning i ditt ärende.</h2>
        <p class="lead u-mt-5">Vi bistår klienter i hela Sverige med juridisk rådgivning för att hjälpa till att bedöma om ett fel juridiskt sett kvalificerar som ett dolt fel och vilka rättigheter och ersättningsmöjligheter som finns. Vårt mål är alltid att hjälpa våra klienter att nå en effektiv och juridiskt hållbar lösning.</p>
      </div>

      <div class="form-card" data-reveal data-reveal-delay="80">
        <?php
        // THE global form component — one definition in
        // template-parts/form-contact.php, used on every page of the site.
        get_template_part(
          'template-parts/form',
          'contact',
          array( 'source' => 'faq' )
        );
        ?>
      </div>
    </div>
  </div>
</section>

<?php
// Structured data for this page, emitted verbatim from the static build.
// If an SEO plugin also outputs FAQ schema, delete this block to avoid duplicates.
?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    { "@type": "Question", "name": "Vad räknas som ett dolt fel i en fastighet?", "acceptedAnswer": { "@type": "Answer", "text": "Ett dolt fel är ett fel som fanns i fastigheten eller bostaden redan vid köpet men som inte gick att upptäcka vid en noggrann undersökning/besiktning. Felet ska inte heller ha varit förväntat med hänsyn till fastighetens ålder, skick och pris. Om dessa kriterier är uppfyllda kan säljaren i vissa fall bli ansvarig enligt jordabalken." } },
    { "@type": "Question", "name": "Hur länge ansvarar säljaren för dolda fel?", "acceptedAnswer": { "@type": "Answer", "text": "Säljaren ansvarar normalt för dolda fel i upp till tio år efter tillträdet vid fastighetsköp. Det innebär dock inte att köparen kan vänta hur länge som helst med att framställa krav. När ett fel upptäcks måste köparen reklamera felet till säljaren inom skälig tid." } },
    { "@type": "Question", "name": "Vad ska man göra om man upptäcker ett dolt fel?", "acceptedAnswer": { "@type": "Answer", "text": "Om du upptäcker ett fel efter ett fastighetsköp bör du: dokumentera felet noggrant, kontakta en sakkunnig eller besiktningsman, reklamera felet skriftligt till säljaren och överväga att kontakta en advokat för juridisk rådgivning. Det är viktigt att agera relativt snabbt för att inte riskera att förlora rätten att kräva ersättning." } },
    { "@type": "Question", "name": "Vilken ersättning kan man få vid dolda fel?", "acceptedAnswer": { "@type": "Answer", "text": "Om ett fel bedöms vara ett dolt fel kan köparen i vissa fall ha rätt till prisavdrag, vilket motsvarar fastighetens värdeminskning, skadestånd för kostnader som uppstått på grund av felet och hävning av köpet i särskilt allvarliga fall. Vilken ersättning som kan bli aktuell beror på felets omfattning och omständigheterna i det enskilda fallet." } },
    { "@type": "Question", "name": "Vad krävs för att vinna en tvist om dolda fel?", "acceptedAnswer": { "@type": "Answer", "text": "För att få rätt i en tvist om dolda fel måste köparen normalt kunna visa att felet fanns i fastigheten vid köpet, att felet inte gick att upptäcka vid en noggrann undersökning, besiktning, och att felet inte var förväntat med hänsyn till fastighetens ålder och skick. Bevisning kan exempelvis bestå av besiktningsprotokoll, tekniska utlåtanden och annan dokumentation." } },
    { "@type": "Question", "name": "Måste en tvist om dolda fel gå till domstol?", "acceptedAnswer": { "@type": "Answer", "text": "Nej, många tvister löses genom förhandling mellan köpare och säljare. I vissa fall kan parterna nå en förlikning utan att behöva gå till domstol. Om parterna inte kan enas kan tvisten i stället prövas i domstol." } },
    { "@type": "Question", "name": "När bör man kontakta en advokat vid dolda fel?", "acceptedAnswer": { "@type": "Answer", "text": "Det kan vara klokt att kontakta en advokat om du misstänker att ett fel är ett dolt fel, om säljaren bestrider ansvar, om kostnaderna för felet är betydande eller om tvisten riskerar att gå till domstol. En advokat kan hjälpa till att analysera situationen och föra klientens talan i förhandling eller domstol." } },
    { "@type": "Question", "name": "Kan man kräva ersättning även om huset är gammalt?", "acceptedAnswer": { "@type": "Answer", "text": "Ja, men bedömningen påverkas av husets ålder och skick. Äldre hus kan förväntas ha vissa brister, vilket innebär att färre fel klassificeras som dolda fel. Samtidigt kan även äldre fastigheter ha fel som anses vara dolda om de är oväntade och inte gick att upptäcka vid köpet." } }
  ]
}
</script>

<?php
get_footer();
