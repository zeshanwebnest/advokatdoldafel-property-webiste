<?php
/**
 * Template Name: Allmänna villkor
 *
 * The firm's terms of business.
 *
 * Built from the static page "allmanna-villkor.html". The copy is the client's and is fixed —
 * see README.md in the theme root before editing any of it.
 *
 * @package advantage-dolda-fel
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Preload the hero so the largest paint is requested before the CSS resolves.
adf_preload_hero(
	'assets/images/property/hero-law-library-wide-1800.webp',
	'assets/images/property/hero-law-library-wide-1200.webp 1200w, assets/images/property/hero-law-library-wide-1800.webp 1800w, assets/images/property/hero-law-library-wide-2400.webp 2400w',
	'100vw'
);

get_header();
?>

<!-- ===================== 1. HERO ===================== -->
<section class="hero hero--estate" aria-labelledby="hero-title">
  <div class="hero__frame">
    <div class="hero__media" aria-hidden="true">
      <img src="<?php echo ADF_URI; ?>/assets/images/property/hero-law-library-wide-1800.webp"
           srcset="<?php echo ADF_URI; ?>/assets/images/property/hero-law-library-wide-1200.webp 1200w,
                   <?php echo ADF_URI; ?>/assets/images/property/hero-law-library-wide-1800.webp 1800w,
                   <?php echo ADF_URI; ?>/assets/images/property/hero-law-library-wide-2400.webp 2400w"
           sizes="100vw" width="1800" height="1013"
           fetchpriority="high" decoding="async" alt="">
    </div>

    <div class="container hero__body">
      <span class="eyebrow-rule eyebrow-rule--dark" aria-hidden="true"></span>

      <h1 class="hero__title" id="hero-title">Allmänna Villkor - Advantage<br>Advokatbyrå</h1>

      <p class="hero__lead">Dessa allmänna villkor (”Villkoren”) gäller för samtliga uppdrag som Advantage Advokatbyrå AB (org. nr. 556849-0345), nedan ”Advantage”, åtar sig, om inte annat uttryckligen avtalats skriftligen med klienten. Villkoren ska tillämpas och tolkas i enlighet med Sveriges Advokatsamfunds vägledande regler om god advokatsed samt tillämplig lag.</p>

      <div class="btn-row">
        <a class="btn btn--gold" href="<?php echo adf_page_url( 'kontakta-oss' ); ?>">
          Fråga en expert <span class="icon icon--arrow" aria-hidden="true"></span>
        </a>
      </div>
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

<!-- ===================== 2. VILLKOREN ===================== -->
<section class="section section--lg section--alt" aria-label="Villkoren i punktform">
  <div class="container">
    <div class="terms-sheet" data-reveal>
      <ol class="terms-list">
      <li class="terms-item" data-reveal>
        <h2 class="terms-item__title">Uppdrag och uppdragsförhållande</h2>
        <div class="prose">
          <p>Ett uppdrag anses etablerat när Advantage har bekräftat uppdraget muntligen eller skriftligen, eller när Advantage påbörjar arbete för klientens räkning, eller när klienten skrivit under en fullmakt. Uppdragets omfattning framgår av uppdragsbekräftelse eller annan överenskommelse. Uppdraget omfattar endast de frågor som uttryckligen har avtalats och inkluderar inte löpande rådgivning, bevakning av rättsutveckling eller tidsfrister, om inte annat särskilt avtalats.</p>
        </div>
      </li>

      <li class="terms-item" data-reveal>
        <h2 class="terms-item__title">Utförande av uppdraget</h2>
        <div class="prose">
          <p>Advantage utför uppdraget med den omsorg, självständighet och professionalism som följer av god advokatsed. Advantage har rätt att använda advokater, biträdande jurister och annan personal inom byrån för uppdragets genomförande.</p>
        </div>
      </li>

      <li class="terms-item" data-reveal>
        <h2 class="terms-item__title">Tystnadsplikt</h2>
        <div class="prose">
          <p>Advantage och dess medarbetare omfattas av en långtgående tystnadsplikt enligt tillämplig lag och Sveriges Advokatsamfunds vägledande regler om god advokatsed. All information som lämnas inom ramen för uppdraget behandlas konfidentiellt och får inte röjas för utomstående annat än i den utsträckning som följer av lag, god advokatsed eller klientens uttryckliga samtycke.</p>
        </div>
      </li>

      <li class="terms-item" data-reveal>
        <h2 class="terms-item__title">Klientens medverkan</h2>
        <div class="prose">
          <p>Klienten ansvarar för att tillhandahålla korrekta, fullständiga och relevanta uppgifter i rätt tid. Advantage ansvarar inte för följder av att underlag eller information från klienten är felaktig, ofullständig eller missvisande.</p>
        </div>
      </li>

      <li class="terms-item" data-reveal>
        <h2 class="terms-item__title">Arvode och förskottsbetalning</h2>
        <div class="prose">
          <p>Arvode fastställs enligt vad som överenskommits med klienten i ett specifikt ärende. Om inte annat avtalats baseras arvodet på nedlagd tid, med beaktande av uppdragets art, omfattning, komplexitet, tidsåtgång och ansvar.</p>
          <p>Advantage kan, med iakttagande av god advokatsed, begära förskottsbetalning innan arbete påbörjas eller innan kostnader och utlägg görs för klientens räkning. Förskottsbetalda medel sätts in på klientmedelskonto, avskilt från Advantages egna medel, och hanteras i enlighet med regler om klientmedel. Advantage har rätt att använda sådana medel för betalning av förfallna fakturor, utan att detta påverkar klientens rätt att framställa invändningar mot fakturorna, om inte förskottet har betalats för ett särskilt angivet ändamål.</p>
        </div>
      </li>

      <li class="terms-item" data-reveal>
        <h2 class="terms-item__title">Kostnader och utlägg</h2>
        <div class="prose">
          <p>Klienten ska ersätta Advantage för skäliga kostnader och utlägg som uppkommer i samband med uppdragets utförande, såsom avgifter, resor, översättningar och kostnader för externa rådgivare. Samtliga belopp anges exklusive mervärdesskatt, om inte annat uttryckligen anges.</p>
        </div>
      </li>

      <li class="terms-item" data-reveal>
        <h2 class="terms-item__title">Fakturering och betalning</h2>
        <div class="prose">
          <p>Om inte annat avtalats sker fakturering genom att faktura skickas via e-post eller vanlig post. Fakturering sker normalt genom delfakturering eller fakturering a conto, vanligtvis månadsvis. Delfaktura avser slutligt arvode för arbete hänförligt till den period som fakturan avser. Fakturering a conto avser preliminärt arvode utan direkt koppling till visst arbetsmoment. Slutlig reglering sker genom slutfaktura. Vid försenad betalning debiteras dröjsmålsränta enligt räntelagen.</p>
        </div>
      </li>

      <li class="terms-item" data-reveal>
        <h2 class="terms-item__title">Ansvar för rättegångskostnader och rättsskyddsförsäkring</h2>
        <div class="prose">
          <p>Om uppdraget avser en tvist kan den part som förlorar tvisten helt eller delvis åläggas att ersätta motpartens rättegångskostnader, inklusive advokatarvoden. Oavsett utgången i tvisten är klienten skyldig att ersätta Advantage för arbete och utlägg i enlighet med dessa villkor. Om klienten har rättsskyddsförsäkring kan sådan försäkring, beroende på försäkringsvillkoren, komma att täcka vissa kostnader. Klientens betalningsansvar gentemot Advantage påverkas dock inte av försäkringens omfattning eller eventuella ersättning.</p>
        </div>
      </li>

      <li class="terms-item" data-reveal>
        <h2 class="terms-item__title">Ansvar och ansvarsbegränsning</h2>
        <div class="prose">
          <p>Advantages ansvar är begränsat till vad som följer av tvingande lag och god advokatsed. Advantage ansvarar inte för indirekt skada eller följdskada. Advantage ansvarar inte heller för råd eller åtgärder som grundar sig på information från klienten eller tredje man som visar sig vara felaktig eller ofullständig.</p>
        </div>
      </li>

      <li class="terms-item" data-reveal>
        <h2 class="terms-item__title">Arkivering</h2>
        <div class="prose">
          <p>Handlingar i ett uppdrag arkiveras under den tid som följer av lag och Advokatsamfundets regler. Därefter kan handlingarna komma att förstöras.</p>
        </div>
      </li>

      <li class="terms-item" data-reveal>
        <h2 class="terms-item__title">Uppdragets upphörande</h2>
        <div class="prose">
          <p>Både klienten och Advantage har rätt att avsluta uppdraget i enlighet med god advokatsed och tillämplig lag.</p>
        </div>
      </li>

      <li class="terms-item" data-reveal>
        <h2 class="terms-item__title">Tvistelösning</h2>
        <div class="prose">
          <p>Med undantag för vad som anges nedan ska tvist som uppstår med anledning av dessa villkor, uppdraget eller Advantages rådgivning eller arbetsresultat slutligt avgöras genom skiljedom enligt vid var tid gällande skiljedomsregler för SCC Skiljedomsinstitut. Skiljeförfarandets säte ska vara Stockholm, Sverige, och språket ska vara svenska, om inte annat avtalats. Skiljeförfarandet ska omfattas av sekretess, med de undantag som följer av lag, myndighetsbeslut, försäkringsförhållanden eller parts behov av att tillvarata sin rätt.</p>
          <p>Vid tvist mellan Advantage eller Advokat eller biträdande jurist vid byrån och en konsument har klienten, om tvisten inte kan lösas i samförstånd, rätt att få tvisten prövad av Sveriges Advokatsamfunds konsumenttvistnämnd. Med konsument avses fysisk person som handlar för ändamål som faller utanför näringsverksamhet.</p>
          <p>Oavsett vad som anges ovan har Advantage alltid rätt att driva in förfallna fordringar genom ansökan om betalningsföreläggande eller genom att väcka talan vid allmän domstol. Advantage har därvid rätt att väcka talan vid domstol där klienten har sitt säte eller hemvist, där klientens egendom finns eller vid Stockholms tingsrätt.</p>
        </div>
      </li>
      </ol>
    </div>
  </div>
</section>

<!-- ===================== 3. FRÅGA VÅRA EXPERTER ===================== -->
<section class="section section--lg" aria-labelledby="experter-title">
  <div class="container">
    <div class="expert-band expert-band--light" data-reveal>
      <div class="expert-band__body">
        <span class="eyebrow-rule" aria-hidden="true"></span>
        <h2 class="h2" id="experter-title">Har du juridiska frågor?<br>Fråga våra experter!</h2>
        <div class="btn-row u-mt-6">
          <a class="btn btn--gold" href="<?php echo adf_page_url( 'kontakta-oss' ); ?>">
            Fråga en expert <span class="icon icon--arrow" aria-hidden="true"></span>
          </a>
        </div>
      </div>
      <figure class="expert-band__portrait">
        <img src="<?php echo ADF_URI; ?>/assets/images/team/peter-tagestam.webp" width="1080" height="1080"
             loading="lazy" decoding="async" alt="Peter Tagestam">
      </figure>
    </div>
  </div>
</section>

<!-- ===================== 4. KONTAKTA OSS FÖR RÅDGIVNING ===================== -->
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
          array( 'source' => 'allmanna-villkor' )
        );
        ?>
      </div>
    </div>
  </div>
</section>

<?php
get_footer();
