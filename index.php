<?php
require_once __DIR__ . '/config.php';

$stats     = db()->query('SELECT * FROM stats ORDER BY sort_order, id')->fetchAll();
$timeline  = db()->query('SELECT * FROM timeline_events ORDER BY sort_order, id')->fetchAll();
$videos    = db()->query("SELECT * FROM media_items WHERE type='video' ORDER BY sort_order, id")->fetchAll();
$photos    = db()->query("SELECT * FROM media_items WHERE type='photo' ORDER BY sort_order, id")->fetchAll();
$docs      = db()->query("SELECT * FROM media_items WHERE type='document' ORDER BY sort_order, id")->fetchAll();
$sources   = db()->query('SELECT * FROM sources ORDER BY sort_order, id')->fetchAll();

$categories = [];
foreach ($photos as $p) {
    if (!empty($p['category']) && !in_array($p['category'], $categories, true)) {
        $categories[] = $p['category'];
    }
}

$heroLines = explode('||', setting('hero_title', 'நிருவனார், பெருந்தலைவர்,||தமிழ்நாடு வீரபாண்டிய கட்டபொம்மன் பண்பாட்டுக் கழகம்.||திரு க. சுப்பு அவர்கள்'));
$aboutParas = explode('||', setting('about_text', ''));
$heroPhoto = setting('hero_photo', '');
$heroPhotos = array_values(array_filter(explode('||', $heroPhoto)));
$heroVideo = setting('hero_video', '');
$docBadgeColors = ['pink', 'green', 'yellow'];
?>
<!doctype html>
<html lang="ta">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= e(setting('site_title', 'K. Subbu')) ?> — <?= e(setting('site_subtitle', '')) ?></title>
<meta name="description" content="<?= e(setting('meta_description', '')) ?>">
<link rel="stylesheet" href="assets/style.css">
</head>
<body>

<header class="topbar">
  <div class="wrap nav">
    <a class="brand" href="index.php">
      <span class="mark">க.சு</span>
      <div><b><?= e(setting('site_title', 'K. SUBBU')) ?></b><small><?= e(setting('site_subtitle', '')) ?></small></div>
    </a>
    <nav class="navlinks" id="navlinks">
      <a href="#top">முகப்பு</a>
      <a href="#about">வாழ்க்கை</a>
      <a href="#timeline">காலவரிசை</a>
      <a href="#videos">பேச்சுகள்</a>
      <a href="#photos">புகைப்படங்கள்</a>
      <a href="#documents">ஆவணங்கள்</a>
      <a href="#legacy">மரபு</a>
      <a href="#sources">தொடர்பு</a>
    </nav>
    <div class="nav-right">
      <a class="navbtn" href="admin/login.php"><?= icon('lock') ?> <span class="label">Admin Login</span></a>
      <button class="burger" aria-label="menu">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
      </button>
    </div>
  </div>
</header>

<main id="top">

<!-- ================= HERO ================= -->
<section class="hero">
  <div class="wrap hero-split">
    <?php if ($heroVideo): ?>
    <div class="hero-photo-col">
      <video class="hero-photo-full" autoplay muted loop playsinline preload="auto" <?= $heroPhotos ? 'poster="uploads/' . e($heroPhotos[0]) . '"' : '' ?> aria-label="<?= e(setting('full_name', SITE_NAME)) ?>">
        <source src="uploads/<?= e($heroVideo) ?>">
      </video>
    </div>
    <?php elseif ($heroPhotos): ?>
    <div class="hero-photo-col">
      <?php if (count($heroPhotos) === 1): ?>
        <img class="hero-photo-full" src="uploads/<?= e($heroPhotos[0]) ?>" alt="<?= e(setting('full_name', SITE_NAME)) ?>">
      <?php else: ?>
        <div class="hero-slideshow" data-slideshow>
          <?php foreach ($heroPhotos as $i => $ph): ?>
            <div class="hero-slide<?= $i === 0 ? ' active' : '' ?>">
              <img src="uploads/<?= e($ph) ?>" alt="<?= e(setting('full_name', SITE_NAME)) ?> — <?= $i + 1 ?>">
            </div>
          <?php endforeach; ?>
          <div class="hero-slide-dots">
            <?php foreach ($heroPhotos as $i => $ph): ?>
              <button type="button" class="dot<?= $i === 0 ? ' active' : '' ?>" data-slide-index="<?= $i ?>" aria-label="Slide <?= $i + 1 ?>"></button>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endif; ?>
    </div>
    <?php endif; ?>
    <div class="hero-text-col">
      <p class="eyebrow"><?= e(setting('hero_eyebrow', '')) ?></p>
      <h1>
        <?php foreach ($heroLines as $i => $line): ?>
          <?= $i === count($heroLines) - 1 ? '<em>' . e($line) . '</em>' : e($line) . '<br>' ?>
        <?php endforeach; ?>
      </h1>
      <p class="lead"><?= e(setting('hero_lead', '')) ?></p>
    </div>
  </div>
  <div class="hero-actions-bar">
    <div class="wrap actions">
      <a class="btn primary" href="#legacy"><?= icon('star') ?> காப்பகத்தைப் பாருங்கள்</a>
      <a class="btn ghost" href="#videos"><?= icon('play') ?> அவரது குரலைக் கேளுங்கள்</a>
    </div>
  </div>
</section>

<!-- ================= STATS ================= -->
<section class="statbar">
  <div class="wrap statgrid">
    <?php foreach ($stats as $s): ?>
      <div class="statitem">
        <div class="ic"><?= icon($s['icon_key']) ?></div>
        <div><b><?= e($s['value']) ?></b><span><?= e($s['label']) ?><?php if (!empty($s['note'])): ?> · <?= e($s['note']) ?><?php endif; ?></span></div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- ================= VIDEO ARCHIVE ================= -->
<section id="videos" class="section dark">
  <div class="wrap">
    <div class="sectionhead">
      <div><p class="eyebrow">VIDEO ARCHIVE</p><h2>அவரது குரலைக் கேளுங்கள்.</h2></div>
      <a class="textlink" href="#videos">அனைத்து வீடியோக்களும் பார்க்க <?= icon('chevron') ?></a>
    </div>
    <div class="video-wrap" data-track>
      <?php if ($videos): ?>
      <div class="video-track">
        <?php foreach ($videos as $v):
          $src = $v['file_path'] ? 'uploads/' . $v['file_path'] : '';
          $ext = $v['external_url'] ? (youtube_embed_url($v['external_url']) ?: $v['external_url']) : '';
          $poster = $v['poster_path'] ? 'uploads/' . $v['poster_path'] : (youtube_thumbnail_url($v['external_url'] ?? null) ?: '');
        ?>
        <div class="video-card" data-video-src="<?= e($src) ?>" data-video-external="<?= e($ext) ?>" style="cursor:pointer" title="<?= e($v['title']) ?><?= $v['source_note'] ? ' — ' . e($v['source_note']) : '' ?>">
          <div class="video-thumb" <?= $poster ? "style=\"background-image:url('".e($poster)."')\"" : '' ?>>
            <?php if (!$poster): ?><?= icon('video', 'thumb-ic') ?><?php endif; ?>
            <?php if ($v['duration']): ?><span class="dur"><?= e($v['duration']) ?></span><?php endif; ?>
            <span class="playbtn"><?= icon('play') ?></span>
          </div>
          <div class="video-body">
            <h3><?= e($v['title']) ?></h3>
            <?php if ($v['caption']): ?><p><?= e($v['caption']) ?></p><?php endif; ?>
            <span class="badge"><?= e($v['source_note']) ?></span>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <button class="scroll-btn right"><?= icon('chevron') ?></button>
      <?php else: ?>
        <div class="empty">வீடியோக்கள் விரைவில் சேர்க்கப்படும்.</div>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- ================= ABOUT ================= -->
<section id="about" class="section">
  <div class="wrap split">
    <div>
      <p class="eyebrow dark">கதை தொடங்கும் இடம்</p>
      <h2><?= e(setting('full_name', '')) ?><br><em><?= e(setting('full_title', '')) ?></em></h2>
    </div>
    <div class="copy">
      <?php foreach ($aboutParas as $i => $para): ?>
        <?php if ($i === count($aboutParas) - 1): ?>
          <p class="note"><?= e($para) ?></p>
        <?php else: ?>
          <p><?= e($para) ?></p>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ================= TIMELINE ================= -->
<section id="timeline" class="section darksec" style="background:var(--cream-2)">
  <div class="wrap">
    <div class="sectionhead">
      <div><p class="eyebrow dark">முக்கிய காலவரிசை</p><h2>ஒரு அரசியல் பயணம்</h2></div>
      <a class="textlink" href="#timeline" style="color:var(--gold-2)">முழு காலவரிசை பார்க்க <?= icon('chevron') ?></a>
    </div>
    <div class="timeline-scroll" data-track>
      <div class="timeline">
        <?php foreach ($timeline as $t): ?>
          <div class="tl-item">
            <div class="tl-year"><?= e($t['event_year']) ?></div>
            <div class="tl-dot"><?= icon('star') ?></div>
            <h4><?= e($t['title']) ?></h4>
            <?php if ($t['subtitle']): ?><p><?= e($t['subtitle']) ?></p><?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- ================= PHOTO MEMORIES ================= -->
<section id="photos" class="section">
  <div class="wrap">
    <div class="sectionhead">
      <div><p class="eyebrow dark">புகைப்பட நினைவுகள்</p><h2>காப்பகப் புகைப்படங்கள்</h2></div>
      <a class="textlink" href="#photos">அனைத்து புகைப்படங்களும் பார்க்க <?= icon('chevron') ?></a>
    </div>
    <?php if ($categories): ?>
    <div class="filter-row">
      <button class="filter active" data-cat="all"><?= icon('photo') ?> அனைத்தும்</button>
      <?php foreach ($categories as $c): ?>
        <button class="filter" data-cat="<?= e($c) ?>"><?= e($c) ?></button>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <?php if ($photos): ?>
    <div class="photo-grid">
      <?php foreach ($photos as $p): ?>
        <div class="photo-card" data-category="<?= e($p['category'] ?? '') ?>" data-title="<?= e($p['title']) ?>">
          <?php if ($p['file_path']): ?>
            <img src="uploads/<?= e($p['file_path']) ?>" alt="<?= e($p['title']) ?>" loading="lazy">
          <?php else: ?>
            <div class="ph-fallback">க.சு</div>
          <?php endif; ?>
          <div class="cap"><?= e($p['title']) ?></div>
        </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
      <div class="empty">புகைப்படங்கள் விரைவில் சேர்க்கப்படும்.</div>
    <?php endif; ?>
  </div>
</section>

<!-- ================= DOCUMENTS ================= -->
<section id="documents" class="section" style="background:var(--cream-2)">
  <div class="wrap">
    <div class="sectionhead">
      <div><p class="eyebrow dark">ஆவணங்கள் பேசுகின்றன</p><h2>கடிதங்கள் • சான்றுகள் • பதிவுகள்</h2></div>
      <a class="textlink" href="#documents" style="color:var(--gold-2)">அனைத்து ஆவணங்களும் பார்க்க <?= icon('chevron') ?></a>
    </div>
    <?php if ($docs): ?>
    <div class="doc-grid">
      <?php foreach ($docs as $i => $d): $isPdf = $d['file_path'] && substr(strtolower($d['file_path']), -4) === '.pdf'; ?>
        <a class="doc-card" data-title="<?= e($d['title']) ?>" href="<?= $d['file_path'] ? 'uploads/' . e($d['file_path']) : ($d['external_url'] ?: '#') ?>" target="_blank">
          <div class="doc-ic"><?= icon($isPdf ? 'pdf' : 'doc') ?></div>
          <h3><?= e($d['title']) ?></h3>
          <?php if ($d['caption']): ?><div class="sub"><?= e($d['caption']) ?></div><?php endif; ?>
          <span class="doc-badge <?= $docBadgeColors[$i % 3] ?>"><?= e($d['source_note']) ?></span>
        </a>
      <?php endforeach; ?>
      <a class="doc-card" href="#documents">
        <div class="doc-ic"><?= icon('folder') ?></div>
        <h3>மற்ற ஆவணங்கள்</h3>
        <div class="sub">விரைவில் சேர்க்கப்படும்</div>
      </a>
    </div>
    <?php else: ?>
      <div class="empty">ஆவணங்கள் விரைவில் சேர்க்கப்படும்.</div>
    <?php endif; ?>
  </div>
</section>

<!-- ================= LEGACY + QUOTE ================= -->
<section id="legacy" class="section">
  <div class="wrap legacy-grid">
    <div class="legacy-card">
      <h3><?= e(setting('legacy_title', '')) ?></h3>
      <p><?= e(setting('legacy_body', '')) ?></p>
      <a class="btn primary small" style="width:fit-content" href="#sources">மேலும் அறிய <?= icon('chevron') ?></a>
    </div>
    <div class="quote-card">
      <?= icon('quote') ?>
      <p>&ldquo;<?= e(setting('quote_text', '')) ?>&rdquo;</p>
      <span><?= e(setting('quote_author', '')) ?></span>
    </div>
  </div>
</section>

<!-- ================= SOURCES ================= -->
<section id="sources" class="section" style="background:var(--cream-2)">
  <div class="wrap">
    <p class="eyebrow dark">மூல ஆதாரங்கள்</p>
    <h2>வரலாறு ஆதாரத்துடன்.</h2>
    <?php if ($sources): ?>
    <div class="sourcegrid">
      <?php foreach ($sources as $s): ?>
        <a href="<?= e($s['url']) ?>" target="_blank"><b><?= e($s['title']) ?></b><span><?= e($s['note']) ?></span></a>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <p class="source-note">குறிப்பு: இத்தளத்தில் உள்ள உறுதிப்படுத்தப்படாத தகவல்கள் "ஆதாரம் தேவை" எனக் குறிக்கப்பட்டுள்ளன. அவை முதன்மை ஆவணங்கள் மூலம் உறுதி செய்யப்பட்ட பிறகே ஒரு நிலையான சாதனைக்கூறாகக் கருதப்பட வேண்டும்.</p>
  </div>
</section>

</main>

<footer>
  <div class="wrap foot">
    <div>
      <b><?= e(setting('site_title', '')) ?></b>
      <p style="margin-top:6px"><?= e(setting('site_subtitle', '')) ?></p>
      <p style="margin-top:14px;font-size:.85rem;color:#aeb9cc">வரலாற்று ஆவணங்களைப் பாதுகாக்கும் காப்பகச் செயல்பாடு. அனைத்து புகைப்படங்கள், வீடியோக்கள் மற்றும் ஆவணங்களும் நிர்வாகியால் பதிவேற்றப்படுகின்றன.</p>
      <div class="social" style="margin-top:16px">
        <a href="#" aria-label="facebook">f</a><a href="#" aria-label="youtube">▶</a><a href="#" aria-label="twitter">𝕏</a><a href="#" aria-label="instagram">◎</a>
      </div>
    </div>
    <div>
      <h4>விரைவு இணைப்புகள்</h4>
      <ul><li><a href="#top">முகப்பு</a></li><li><a href="#about">வாழ்க்கை</a></li><li><a href="#timeline">காலவரிசை</a></li><li><a href="#photos">புகைப்படங்கள்</a></li></ul>
    </div>
    <div>
      <h4>ஆதாரங்கள்</h4>
      <ul><li><a href="#documents">ஆவணங்கள்</a></li><li><a href="#legacy">மரபு</a></li><li><a href="#sources">தொடர்பு</a></li></ul>
    </div>
    <div>
      <h4>தொடர்பு</h4>
      <ul>
        <li><?= e(setting('contact_location', '')) ?></li>
        <li><?= e(setting('contact_email', '')) ?></li>
        <li><?= e(setting('contact_phone', '')) ?></li>
      </ul>
    </div>
  </div>
  <div class="wrap foot-bottom">
    <span>&copy; <?= date('Y') ?> <?= e(setting('site_title', '')) ?> Digital Archive. அனைத்து உரிமைகளும் பாதுகாக்கப்பட்டவை.</span>
    <button class="to-top" aria-label="top"><?= icon('arrow-up') ?></button>
  </div>
</footer>

<script src="assets/app.js"></script>
</body>
</html>
