<?php
require __DIR__ . '/includes/lib.php';
$latest = array_slice(news_published(), 0, 4);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>ツムギテクノロジーズ</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Shippori+Mincho+B1:wght@600;800&family=Zen+Kaku+Gothic+New:wght@400;500;700&display=swap">
<style>
/* Layout: 全面キャンバスの糸が動くヒーロー。以降は左右非対称の2カラム。モチーフは「経糸(基盤)と緯糸(データ)」 */
:root{
  --bg:#F3F6FA; --bg2:#E7EDF5; --surface:#FFFFFF; --fg:#0E1A2B; --muted:#4A5A72; --line:#C9D4E3;
  --accent:#007F8A; --on-accent:#FFFFFF; --warm:#B87400;
  --f-display:"Shippori Mincho B1","Hiragino Mincho ProN","Yu Mincho",serif;
  --f-body:"Zen Kaku Gothic New","Hiragino Kaku Gothic ProN","Yu Gothic",Meiryo,sans-serif;
  --f-mono:"DM Mono",ui-monospace,SFMono-Regular,Menlo,monospace;
  --ease:cubic-bezier(.2,.7,.2,1);
}
@media (prefers-color-scheme: dark){
  :root:not([data-theme="light"]){
    --bg:#09121F; --bg2:#0E1A2C; --surface:#122138; --fg:#E8EEF7; --muted:#9BAAC0; --line:#233550;
    --accent:#3FD0DA; --on-accent:#03232A; --warm:#F2B134; color-scheme:dark;
  }
}
:root[data-theme="dark"]{
  --bg:#09121F; --bg2:#0E1A2C; --surface:#122138; --fg:#E8EEF7; --muted:#9BAAC0; --line:#233550;
  --accent:#3FD0DA; --on-accent:#03232A; --warm:#F2B134; color-scheme:dark;
}

*,*::before,*::after{box-sizing:border-box}
html{scroll-behavior:smooth}
body{
  margin:0;
  background:var(--bg); color:var(--fg);
  font-family:var(--f-body); font-size:16px; line-height:1.9;
  line-break:strict; overflow-x:hidden;
}
body::before{content:"";position:fixed;inset:0 0 auto;height:env(safe-area-inset-top,0px);z-index:30;background:var(--bg)}
h1,h2,h3,p,dl,dd,ul{margin:0;padding:0}
ul{list-style:none}
a{color:inherit}
section{scroll-margin-top:72px}
:focus-visible{outline:2px solid var(--accent);outline-offset:3px}

.wrap{
  width:100%; max-width:1240px; margin-inline:auto;
  padding-left:max(16px,env(safe-area-inset-left,0px));
  padding-right:max(16px,env(safe-area-inset-right,0px));
}
.sec{padding-block:clamp(72px,10vw,140px)}
.eyebrow{
  font-family:var(--f-mono); font-size:.75rem; letter-spacing:.16em; text-transform:uppercase;
  color:var(--accent); display:flex; align-items:center; gap:12px; margin-bottom:20px;
}
.eyebrow::before{content:"";width:28px;height:1px;background:currentColor}
h2{
  font-family:var(--f-display); font-weight:800; line-height:1.3;
  font-size:clamp(1.8rem,4.2vw,3.1rem); letter-spacing:.01em; text-wrap:balance;
}
h3{font-family:var(--f-display); font-weight:800; font-size:1.3rem; line-height:1.5}
.muted{color:var(--muted)}

/* ===== header ===== */
header.site{
  position:sticky; top:env(safe-area-inset-top,0px); z-index:40;
  background:color-mix(in srgb,var(--bg) 88%,transparent);
  backdrop-filter:blur(10px); -webkit-backdrop-filter:blur(10px);
  border-bottom:1px solid var(--line);
}
.bar{display:flex;align-items:center;justify-content:space-between;gap:16px;min-height:64px}
.logo{display:flex;align-items:center;gap:10px;text-decoration:none;min-width:0}
.logo .mark{flex:none;width:30px;height:30px;fill:none;stroke-width:2.4;stroke-linecap:round}
.logo .mark path:first-child{stroke:var(--accent)}
.logo .mark path:last-child{stroke:var(--warm)}
.logo .mark path{stroke-dasharray:1;animation:drawIn 1.6s var(--ease) both}
.logo .mark path:last-child{animation-delay:.25s}
.logo b{font-family:var(--f-mono);font-weight:500;letter-spacing:.18em;font-size:.95rem}
.logo small{font-size:.72rem;color:var(--muted);letter-spacing:.06em;white-space:nowrap}
.logo .names{display:flex;flex-direction:column;line-height:1.25;min-width:0}
nav#nav{display:none}
nav#nav a{text-decoration:none;font-size:.9rem;position:relative;padding:6px 2px}
nav#nav a:not(.cta)::after{
  content:"";position:absolute;left:0;right:0;bottom:0;height:2px;background:var(--accent);
  transform:scaleX(0);transform-origin:right;transition:transform .4s var(--ease);
}
nav#nav a:not(.cta):hover::after{transform:scaleX(1);transform-origin:left}
nav#nav a.cta{background:var(--accent);color:var(--on-accent);padding:8px 18px;border-radius:2px;font-weight:700}
.menu{
  font:inherit;font-size:.85rem;background:none;color:var(--fg);border:1px solid var(--line);
  padding:8px 14px;border-radius:2px;cursor:pointer;
}
header.site.open nav#nav{
  display:flex;flex-direction:column;gap:4px;position:absolute;left:0;right:0;top:100%;
  background:var(--bg);border-bottom:1px solid var(--line);
  padding:12px max(16px,env(safe-area-inset-right,0px)) 20px max(16px,env(safe-area-inset-left,0px));
}
header.site.open nav#nav a{padding:12px 2px;font-size:1rem}
header.site.open nav#nav a.cta{text-align:center;margin-top:8px}
.progress{
  position:absolute;left:0;bottom:-1px;height:2px;width:100%;transform:scaleX(0);transform-origin:left;
  background:linear-gradient(90deg,var(--accent),var(--warm));
}
@media (min-width:900px){
  nav#nav{display:flex;align-items:center;gap:28px}
  .menu{display:none}
}

/* ===== hero ===== */
.hero{position:relative;overflow:hidden;padding-block:clamp(56px,9vw,112px) clamp(64px,9vw,120px)}
.hero canvas{position:absolute;left:0;top:0;pointer-events:none;z-index:0}
.hero::before{
  content:"";position:absolute;inset:0;z-index:0;pointer-events:none;
  background:radial-gradient(460px circle at var(--mx,72%) var(--my,28%),color-mix(in srgb,var(--accent) 18%,transparent),transparent 70%);
}
.hero::after{
  content:"";position:absolute;left:0;right:0;bottom:0;height:90px;z-index:0;pointer-events:none;
  background:linear-gradient(transparent,var(--bg));
}
.hero-inner{position:relative;z-index:1}
.hero h1{
  font-family:var(--f-display);font-weight:800;line-height:1.22;letter-spacing:.01em;
  font-size:clamp(2.5rem,8.2vw,6rem);margin-bottom:28px;
}
.hero h1 .ch{display:inline-block;animation:rise .9s var(--ease) both;animation-delay:calc(var(--i) * 55ms + 150ms)}
.hl{position:relative;display:inline-block}
.hl::after{
  content:"";position:absolute;left:0;right:0;bottom:.02em;height:.07em;background:var(--accent);
  transform-origin:left;animation:underline 1.1s var(--ease) 1.2s both;
}
.lead{max-width:34em;font-size:clamp(1rem,1.6vw,1.12rem);color:var(--muted);margin-bottom:36px;animation:fade .9s var(--ease) 1s both}
.btns{display:flex;flex-wrap:wrap;gap:14px;margin-bottom:56px;animation:fade .9s var(--ease) 1.15s both}
.btn{
  display:inline-flex;align-items:center;gap:12px;font:inherit;font-weight:700;text-decoration:none;cursor:pointer;
  padding:15px 26px;border-radius:2px;border:1px solid var(--accent);background:var(--accent);color:var(--on-accent);
  transition:transform .3s var(--ease),background .3s;
}
.btn .arr{display:inline-block;transition:transform .3s var(--ease)}
.btn:hover .arr{transform:translateX(6px)}
.btn:hover{transform:translateY(-2px)}
.btn.ghost{background:transparent;color:var(--fg);border-color:var(--fg)}
.meta{display:flex;flex-wrap:wrap;gap:12px 40px;animation:fade .9s var(--ease) 1.3s both}
.meta dt{font-family:var(--f-mono);font-size:.7rem;letter-spacing:.14em;text-transform:uppercase;color:var(--muted)}
.meta dd{font-size:.95rem;line-height:1.6}

/* ===== marquee ===== */
.marquee{border-block:1px solid var(--line);overflow:hidden;background:var(--bg)}
.track{display:flex;width:max-content;animation:slide 48s linear infinite}
.marquee:hover .track{animation-play-state:paused}
.track span{
  display:flex;align-items:center;gap:28px;padding:18px 0 18px 28px;
  font-family:var(--f-mono);font-size:.82rem;letter-spacing:.14em;text-transform:uppercase;white-space:nowrap;color:var(--muted);
}
.track span::after{content:"";width:6px;height:6px;border-radius:50%;background:var(--warm)}

/* ===== about ===== */
.about-grid{display:grid;gap:56px;grid-template-columns:1fr}
.about-grid > *{min-width:0}
.about p.body{max-width:34em;color:var(--muted)}
.about p.body + p.body{margin-top:1.2em}
.weave-defs{margin-top:36px;display:grid;gap:0;border-top:1px solid var(--line)}
.weave-defs div{display:grid;grid-template-columns:7.5em 1fr;gap:16px;padding:18px 0;border-bottom:1px solid var(--line)}
.weave-defs dt{font-family:var(--f-mono);font-size:.78rem;letter-spacing:.14em;text-transform:uppercase}
.weave-defs dt span{display:block;font-family:var(--f-display);font-size:1.15rem;letter-spacing:0;text-transform:none;font-weight:800}
.weave-defs div:first-child dt span{color:var(--accent)}
.weave-defs div:last-child dt span{color:var(--warm)}
.weave-defs dd{color:var(--muted);font-size:.95rem;line-height:1.8}
.loom-box{background:var(--surface);border:1px solid var(--line);padding:clamp(16px,3vw,32px);border-radius:4px}
#loom{display:block;width:100%;height:auto;max-width:100%}
#loom .warp{stroke:var(--line);stroke-width:1.5}
#loom .thread{fill:none;stroke:var(--accent);stroke-width:2.6;stroke-linecap:round;stroke-linejoin:round;stroke-dasharray:1}
#loom .shuttle{fill:var(--warm)}
.js .draw:not(.in) .dr{stroke-dashoffset:1}
.dr{stroke-dasharray:1;stroke-dashoffset:0;transition:stroke-dashoffset 3.2s var(--ease)}
.loom-cap{margin-top:14px;font-family:var(--f-mono);font-size:.7rem;letter-spacing:.12em;color:var(--muted);text-transform:uppercase}
@media (min-width:900px){.about-grid{grid-template-columns:1.05fr .95fr;gap:80px;align-items:center}}

/* ===== services ===== */
.sec-head{display:flex;flex-wrap:wrap;justify-content:space-between;align-items:flex-end;gap:24px;margin-bottom:48px}
.sec-head p{max-width:26em;color:var(--muted)}
.cards{display:grid;gap:20px;grid-template-columns:repeat(auto-fit,minmax(min(100%,420px),1fr))}
.card{
  position:relative;overflow:hidden;background:var(--surface);border:1px solid var(--line);border-radius:3px;
  padding:clamp(24px,3.4vw,40px);min-width:0;transition:border-color .4s,transform .5s var(--ease);
}
.card::after{
  content:"";position:absolute;left:0;right:0;bottom:0;height:3px;background:linear-gradient(90deg,var(--accent),var(--warm));
  transform:scaleX(0);transform-origin:left;transition:transform .6s var(--ease);
}
.card:hover{border-color:var(--accent);transform:translateY(-4px)}
.card:hover::after{transform:scaleX(1)}
.card .kind{
  display:flex;justify-content:space-between;gap:12px;margin-bottom:22px;
  font-family:var(--f-mono);font-size:.7rem;letter-spacing:.16em;text-transform:uppercase;color:var(--muted);
}
.card .kind b{font-weight:500;color:var(--accent)}
.card .kind b.weft{color:var(--warm)}
.card p{color:var(--muted);margin-top:12px}
.tags{display:flex;flex-wrap:wrap;gap:8px;margin-top:22px}
.tags span{font-family:var(--f-mono);font-size:.72rem;letter-spacing:.06em;padding:4px 10px;border:1px solid var(--line);border-radius:2px}

/* ===== numbers ===== */
.stats-band{background:var(--bg2);border-block:1px solid var(--line)}
.stats{display:grid;gap:40px 24px;grid-template-columns:repeat(auto-fit,minmax(min(100%,220px),1fr))}
.stat{min-width:0}
.stat .n{font-family:var(--f-display);font-weight:800;line-height:1.1;font-size:clamp(2.8rem,6.4vw,4.6rem);font-variant-numeric:tabular-nums}
.stat .n small{font-size:.38em;margin-left:.2em;color:var(--accent)}
.stat p{margin-top:8px;color:var(--muted);font-size:.9rem;line-height:1.7}

/* ===== process ===== */
.process-grid{display:grid;gap:48px;grid-template-columns:1fr}
.process-grid > *{min-width:0}
.sticky-head p{margin-top:20px;max-width:24em;color:var(--muted)}
.steps{position:relative;padding-left:44px;display:grid;gap:48px}
.line{position:absolute;left:10px;top:12px;bottom:12px;width:2px;background:var(--line)}
.fill{position:absolute;inset:0;background:var(--accent);transform:scaleY(0);transform-origin:top}
.step{position:relative}
.step .dot{
  position:absolute;left:-44px;top:6px;width:22px;height:22px;border-radius:50%;
  border:2px solid var(--line);background:var(--bg);transition:background .4s,border-color .4s,box-shadow .4s;
}
.step.on .dot{background:var(--accent);border-color:var(--accent);box-shadow:0 0 0 6px color-mix(in srgb,var(--accent) 22%,transparent)}
.step .term{font-family:var(--f-mono);font-size:.72rem;letter-spacing:.14em;color:var(--warm);text-transform:uppercase;margin-bottom:6px}
.step p{margin-top:8px;color:var(--muted);max-width:32em}
@media (min-width:900px){
  .process-grid{grid-template-columns:.8fr 1.2fr;gap:80px}
  .sticky-head{position:sticky;top:calc(env(safe-area-inset-top,0px) + 96px);align-self:start}
}

/* ===== works ===== */
.works{display:grid;gap:20px;grid-template-columns:repeat(auto-fit,minmax(min(100%,320px),1fr))}
.work{min-width:0}
.work-in{
  height:100%;background:var(--surface);border:1px solid var(--line);border-radius:3px;padding:28px;
  transition:transform .25s var(--ease),border-color .3s;transform-style:preserve-3d;
}
.work-in:hover{border-color:var(--accent)}
.work .who{font-family:var(--f-mono);font-size:.72rem;letter-spacing:.12em;color:var(--muted);text-transform:uppercase}
.work .big{font-family:var(--f-display);font-weight:800;font-size:clamp(2rem,4vw,2.7rem);line-height:1.2;margin:14px 0 4px;font-variant-numeric:tabular-nums}
.work .big small{font-size:.45em;color:var(--accent);margin-left:.15em}
.spark{display:block;width:100%;height:84px;margin:14px 0 18px}
.spark .area{fill:color-mix(in srgb,var(--accent) 14%,transparent);stroke:none}
.spark .ln{fill:none;stroke:var(--accent);stroke-width:2.4;stroke-linecap:round;stroke-linejoin:round}
.spark .end{fill:var(--warm)}
.work p{color:var(--muted);font-size:.93rem;line-height:1.8}

/* ===== news ===== */
.news-grid{display:grid;gap:40px;grid-template-columns:1fr}
.news-grid > *{min-width:0}
.news li{border-top:1px solid var(--line)}
.news li:last-child{border-bottom:1px solid var(--line)}
.news a{
  display:grid;grid-template-columns:auto auto 1fr auto;gap:8px 20px;align-items:baseline;
  padding:22px 4px;text-decoration:none;transition:padding .4s var(--ease),background .3s;
}
.news a:hover{padding-left:18px;background:var(--bg2)}
.news time{font-family:var(--f-mono);font-size:.82rem;color:var(--muted);font-variant-numeric:tabular-nums}
.news .tag{font-family:var(--f-mono);font-size:.68rem;letter-spacing:.1em;padding:2px 10px;border:1px solid var(--accent);color:var(--accent);border-radius:2px;white-space:nowrap}
.news .ttl{min-width:0}
.news .go{transition:transform .3s var(--ease);color:var(--accent)}
.news a:hover .go{transform:translateX(6px)}
@media (max-width:640px){
  .news a{grid-template-columns:auto 1fr}
  .news .ttl{grid-column:1 / -1;order:3}
  .news .go{display:none}
}
@media (min-width:900px){.news-grid{grid-template-columns:.7fr 1.3fr;gap:80px}}

/* ===== careers ===== */
.careers{position:relative;overflow:hidden;background:var(--accent);color:var(--on-accent)}
.careers .wrap{position:relative;z-index:1}
.careers h2{max-width:14em}
.careers p{max-width:30em;margin:20px 0 32px;opacity:.9}
.careers .btn{background:var(--on-accent);color:var(--accent);border-color:var(--on-accent)}
.waves{position:absolute;left:0;bottom:0;width:2400px;height:140px;pointer-events:none;fill:none;stroke:var(--on-accent);stroke-width:1.6;stroke-opacity:.3;animation:wave 26s linear infinite}
.waves.b{bottom:18px;stroke-opacity:.18;animation-duration:38s;animation-direction:reverse}

/* ===== contact ===== */
.contact-grid{display:grid;gap:48px;grid-template-columns:1fr}
.contact-grid > *{min-width:0}
.info{margin-top:32px;display:grid;gap:18px}
.info div{display:grid;grid-template-columns:5.5em 1fr;gap:12px;padding-top:16px;border-top:1px solid var(--line)}
.info dt{font-family:var(--f-mono);font-size:.7rem;letter-spacing:.14em;text-transform:uppercase;color:var(--muted);padding-top:4px}
.info dd{overflow-wrap:anywhere}
form{display:grid;gap:20px}
.field{display:grid;gap:8px}
.field label{font-size:.85rem;font-weight:700}
.field input,.field select,.field textarea{
  width:100%;font:inherit;color:var(--fg);background:var(--surface);
  border:1px solid var(--line);border-radius:2px;padding:12px 14px;
  transition:border-color .3s,box-shadow .3s;
}
.field textarea{min-height:140px;resize:vertical;line-height:1.7}
.field input:focus,.field select:focus,.field textarea:focus{outline:none;border-color:var(--accent);box-shadow:0 0 0 3px color-mix(in srgb,var(--accent) 25%,transparent)}
.status{min-height:1.6em;font-size:.9rem;color:var(--accent)}
form .btn{justify-self:start}
@media (min-width:900px){.contact-grid{grid-template-columns:.8fr 1.2fr;gap:80px}}

/* ===== footer ===== */
footer{background:var(--bg2);border-top:1px solid var(--line);padding-block:40px calc(40px + env(safe-area-inset-bottom,0px))}
footer .wrap{display:flex;flex-wrap:wrap;justify-content:space-between;gap:16px 40px;font-size:.82rem;color:var(--muted)}
footer small{font-family:var(--f-mono);letter-spacing:.06em}

.toast{
  position:fixed;left:50%;bottom:calc(24px + env(safe-area-inset-bottom,0px));z-index:60;
  transform:translate(-50%,20px);opacity:0;pointer-events:none;
  background:var(--fg);color:var(--bg);padding:12px 20px;border-radius:2px;font-size:.88rem;
  max-width:calc(100vw - 32px);transition:opacity .4s,transform .4s var(--ease);
}
.toast.show{opacity:1;transform:translate(-50%,0)}

/* ===== reveal ===== */
.rv{transition:opacity .9s var(--ease) var(--d,0ms),transform .9s var(--ease) var(--d,0ms)}
.rv.pre{opacity:.25;transform:translateY(36px)}

/* ===== keyframes ===== */
@keyframes rise{from{opacity:0;transform:translateY(.55em) rotate(2deg)}to{opacity:1;transform:none}}
@keyframes fade{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:none}}
@keyframes underline{from{transform:scaleX(0)}to{transform:scaleX(1)}}
@keyframes drawIn{from{stroke-dashoffset:1}to{stroke-dashoffset:0}}
@keyframes slide{to{transform:translateX(-50%)}}
@keyframes wave{to{transform:translateX(-1200px)}}

@media (prefers-reduced-motion: reduce){
  html{scroll-behavior:auto}
  *,*::before,*::after{animation:none!important;transition:none!important}
  .rv.pre{opacity:1;transform:none}
  .track{flex-wrap:wrap;width:auto}
  .waves{display:none}
}
</style>
</head>
<body>

<header class="site" id="top">
  <div class="wrap bar">
    <a class="logo" href="#top" aria-label="ツムギテクノロジーズ トップへ">
      <svg class="mark" viewBox="0 0 30 30" aria-hidden="true"><path pathLength="1" d="M3 22C9 2 21 2 27 22"/><path pathLength="1" d="M3 8c6 20 18 20 24 0"/></svg>
      <span class="names"><b>TSUMUGI</b><small>ツムギテクノロジーズ</small></span>
    </a>
    <nav id="nav" aria-label="メインメニュー">
      <a href="#services">事業内容</a>
      <a href="#about">私たちについて</a>
      <a href="#works">実績</a>
      <a href="#news">ニュース</a>
      <a href="recruit.html">採用</a>
      <a class="cta" href="#contact">お問い合わせ</a>
    </nav>
    <button class="menu" type="button" aria-expanded="false" aria-controls="nav">メニュー</button>
  </div>
  <div class="progress" id="progress"></div>
</header>

<main>
<section class="hero">
  <canvas id="weave" aria-hidden="true"></canvas>
  <div class="wrap hero-inner">
    <p class="eyebrow">Tsumugi Technologies / Since 2011</p>
    <h1 id="headline" aria-label="データを紡ぎ、事業をつなぐ。">データを<span class="hl">紡ぎ、</span><br>事業をつなぐ。</h1>
    <p class="lead">基幹システムの刷新から、クラウド基盤、データ活用、AI導入まで。ばらばらに動いてきた仕組みを、一本の糸でつなぎ直します。</p>
    <div class="btns">
      <a class="btn" href="#services">事業内容を見る <span class="arr" aria-hidden="true">→</span></a>
      <a class="btn ghost" href="#contact">相談する</a>
    </div>
    <dl class="meta">
      <div><dt>設立</dt><dd>2011年4月</dd></div>
      <div><dt>所在地</dt><dd>東京都渋谷区（架空）</dd></div>
      <div><dt>従業員</dt><dd>428名</dd></div>
      <div><dt>認証</dt><dd>ISMS / ISO 27001</dd></div>
    </dl>
  </div>
</section>

<div class="marquee" aria-hidden="true"><div class="track" id="track"></div></div>

<section class="sec about" id="about">
  <div class="wrap about-grid">
    <div class="rv">
      <p class="eyebrow">About / 私たちについて</p>
      <h2>経糸は基盤。<br>緯糸はデータ。</h2>
      <div style="height:28px"></div>
      <p class="body">織物は、動かない経糸（たていと）と、行き来する緯糸（よこいと）でできています。システムも同じです。止まらない基盤があり、その上をデータが行き来して、はじめて事業という布になります。</p>
      <p class="body">社名のツムギは、糸を紡ぐことから。私たちは基盤を堅く張り、データをつなぎ、その布が長く使えるように手入れを続けます。</p>
      <dl class="weave-defs">
        <div><dt>Warp<span>経糸</span></dt><dd>クラウド基盤、SRE、セキュリティ。24時間365日、止めないための土台。</dd></div>
        <div><dt>Weft<span>緯糸</span></dt><dd>システム連携、API、データ基盤、AI。部門と業務をまたいで流れる仕組み。</dd></div>
      </dl>
    </div>
    <div class="rv">
      <div class="loom-box">
        <svg id="loom" class="draw" viewBox="-14 0 440 320" role="img" aria-label="縦糸と横糸が織り合わされていく図"></svg>
        <p class="loom-cap">11 warp threads / 6 weft passes</p>
      </div>
    </div>
  </div>
</section>

<section class="sec" id="services" style="padding-top:0">
  <div class="wrap">
    <div class="sec-head rv">
      <div>
        <p class="eyebrow">Services / 事業内容</p>
        <h2>四つの事業で、<br>業務の流れを支える。</h2>
      </div>
      <p>それぞれの事業は、経糸（基盤）か緯糸（連携）のどちらかを受け持ちます。組み合わせて依頼することも、一つだけ依頼することもできます。</p>
    </div>
    <div class="cards" data-stagger>
      <article class="card rv">
        <div class="kind"><span>Service 01</span><b class="weft">Weft / 連携</b></div>
        <h3>システムインテグレーション</h3>
        <p>基幹システムの刷新と、既存資産との段階的な連携。止められない業務を止めずに移行します。</p>
        <div class="tags"><span>ERP連携</span><span>API設計</span><span>段階移行</span></div>
      </article>
      <article class="card rv">
        <div class="kind"><span>Service 02</span><b>Warp / 基盤</b></div>
        <h3>クラウド基盤・SRE</h3>
        <p>設計から移行、24時間365日の運用まで。稼働率99.99%を契約で約束します。</p>
        <div class="tags"><span>AWS</span><span>Kubernetes</span><span>Terraform</span></div>
      </article>
      <article class="card rv">
        <div class="kind"><span>Service 03</span><b class="weft">Weft / 連携</b></div>
        <h3>データ基盤・AI活用</h3>
        <p>散らばったデータを一つの基盤に集め、需要予測や異常検知を現場の業務に組み込みます。</p>
        <div class="tags"><span>Kafka</span><span>PostgreSQL</span><span>dbt</span><span>MLOps</span></div>
      </article>
      <article class="card rv">
        <div class="kind"><span>Service 04</span><b>Warp / 基盤</b></div>
        <h3>セキュリティ・ガバナンス</h3>
        <p>ゼロトラストの設計、脆弱性診断、ISMS認証の取得支援まで。事業のスピードを落とさない守りを作ります。</p>
        <div class="tags"><span>ゼロトラスト</span><span>SOC</span><span>ISMS</span></div>
      </article>
    </div>
  </div>
</section>

<section class="stats-band" id="numbers">
  <div class="wrap sec">
    <div class="stats rv" data-stagger>
      <div class="stat"><div class="n"><span class="num" data-target="312" data-dec="0">312</span><small>社</small></div><p>導入実績（2011年から累計）</p></div>
      <div class="stat"><div class="n"><span class="num" data-target="99.99" data-dec="2">99.99</span><small>%</small></div><p>運用中基盤の稼働率（直近12か月）</p></div>
      <div class="stat"><div class="n"><span class="num" data-target="1480" data-dec="0">1,480</span><small>本</small></div><p>本番稼働中の連携API</p></div>
      <div class="stat"><div class="n"><span class="num" data-target="15" data-dec="0">15</span><small>年目</small></div><p>2011年4月の創業から</p></div>
    </div>
  </div>
</section>

<section class="sec" id="process">
  <div class="wrap process-grid">
    <div class="sticky-head rv">
      <p class="eyebrow">Process / 進め方</p>
      <h2>小さく試し、<br>止めずに切り替える。</h2>
      <p>大きな刷新ほど、最初の2週間が結果を左右します。現場の話を聞く時間を、最も長く取ります。</p>
    </div>
    <div class="steps" id="steps">
      <div class="line"><div class="fill" id="fill"></div></div>
      <div class="step"><span class="dot"></span><p class="term">2週間</p><h3>課題の棚卸し</h3><p>現行システム、データ、運用の実態を洗い出し、優先順位を決めます。</p></div>
      <div class="step"><span class="dot"></span><p class="term">4〜6週間</p><h3>設計と試作</h3><p>構成を設計し、最小の機能で動く試作を作って確かめます。</p></div>
      <div class="step"><span class="dot"></span><p class="term">3〜9か月</p><h3>開発と移行</h3><p>小さく分けてリリースし、業務を止めずに切り替えます。</p></div>
      <div class="step"><span class="dot"></span><p class="term">契約期間中</p><h3>運用と改善</h3><p>24時間365日の監視と、四半期ごとの改善提案を続けます。</p></div>
    </div>
  </div>
</section>

<section class="sec" id="works" style="padding-top:0">
  <div class="wrap">
    <div class="sec-head rv">
      <div>
        <p class="eyebrow">Works / 実績</p>
        <h2>数字で語れる成果を。</h2>
      </div>
      <p>事例は一部を抜粋しています。社名は架空のものです。</p>
    </div>
    <div class="works" data-stagger>
      <article class="work rv"><div class="work-in">
        <p class="who">金融 / 北陵銀行</p>
        <p class="big">840→120<small>ms</small></p>
        <svg class="spark draw" viewBox="0 0 270 80" preserveAspectRatio="none" aria-hidden="true"><path class="area" d="M0 8 L30 14 L60 12 L90 26 L120 30 L150 46 L180 52 L210 62 L240 66 L270 70 L270 80 L0 80Z"/><path class="ln dr" pathLength="1" d="M0 8 L30 14 L60 12 L90 26 L120 30 L150 46 L180 52 L210 62 L240 66 L270 70"/></svg>
        <h3>勘定系API連携基盤</h3>
        <p>メインフレームを残したままAPI化し、応答時間（p95）を840msから120msへ短縮しました。</p>
      </div></article>
      <article class="work rv"><div class="work-in">
        <p class="who">物流 / ハコビ物流</p>
        <p class="big">−38<small>%</small></p>
        <svg class="spark draw" viewBox="0 0 270 80" preserveAspectRatio="none" aria-hidden="true"><path class="area" d="M0 10 L30 12 L60 22 L90 20 L120 34 L150 40 L180 44 L210 56 L240 60 L270 66 L270 80 L0 80Z"/><path class="ln dr" pathLength="1" d="M0 10 L30 12 L60 22 L90 20 L120 34 L150 40 L180 44 L210 56 L240 60 L270 66"/></svg>
        <h3>需要予測AIによる配送計画</h3>
        <p>全国62拠点の需要を日次で予測し、配送遅延を38%減らしました。</p>
      </div></article>
      <article class="work rv"><div class="work-in">
        <p class="who">製造 / 三角精機</p>
        <p class="big">2.4<small>億件 / 日</small></p>
        <svg class="spark draw" viewBox="0 0 270 80" preserveAspectRatio="none" aria-hidden="true"><path class="area" d="M0 70 L30 66 L60 60 L90 62 L120 48 L150 40 L180 34 L210 24 L240 18 L270 10 L270 80 L0 80Z"/><path class="ln dr" pathLength="1" d="M0 70 L30 66 L60 60 L90 62 L120 48 L150 40 L180 34 L210 24 L240 18 L270 10"/></svg>
        <h3>工場IoTデータ基盤</h3>
        <p>14工場、約9,000台の設備から1日2.4億件を集め、異常を検知するまでの時間を秒単位にしました。</p>
      </div></article>
    </div>
  </div>
</section>

<section class="sec news" id="news" style="padding-top:0">
  <div class="wrap news-grid">
    <div class="rv">
      <p class="eyebrow">News / ニュース</p>
      <h2>最新のお知らせ</h2>
      <a class="btn ghost" href="news.php" style="margin-top:28px">一覧を見る <span class="arr" aria-hidden="true">→</span></a>
    </div>
    <ul class="rv">
<?php foreach ($latest as $n): ?>
      <li><a href="news.php?id=<?= (int) $n['id'] ?>"><time datetime="<?= h($n['date']) ?>"><?= h(format_date($n['date'])) ?></time><span class="tag"><?= h($n['category']) ?></span><span class="ttl"><?= h($n['title']) ?></span><span class="go" aria-hidden="true">→</span></a></li>
<?php endforeach; ?>
<?php if (!$latest): ?>
      <li><p class="muted" style="padding:22px 4px">現在、お知らせはありません。</p></li>
<?php endif; ?>
    </ul>
  </div>
</section>

<section class="careers sec" id="careers">
  <svg class="waves" id="wavesA" viewBox="0 0 2400 140" aria-hidden="true"></svg>
  <svg class="waves b" id="wavesB" viewBox="0 0 2400 140" aria-hidden="true"></svg>
  <div class="wrap">
    <p class="eyebrow" style="color:inherit">Careers / 採用</p>
    <h2>一緒に、次の織り手になりませんか。</h2>
    <p>SRE、データエンジニア、プロジェクトマネージャーを募集しています。リモート勤務は週3日まで。技術書の購入と、年2回の海外カンファレンス参加を会社が支援します。</p>
    <a class="btn" href="recruit.html">募集職種を見る <span class="arr" aria-hidden="true">→</span></a>
  </div>
</section>

<section class="sec" id="contact">
  <div class="wrap contact-grid">
    <div class="rv">
      <p class="eyebrow">Contact / お問い合わせ</p>
      <h2>まずは、課題を<br>聞かせてください。</h2>
      <dl class="info">
        <div><dt>所在地</dt><dd>東京都渋谷区神南1-0-0（架空の住所）</dd></div>
        <div><dt>電話</dt><dd>03-0000-0000（平日 9:30〜18:00）</dd></div>
        <div><dt>メール</dt><dd>info@tsumugi-tech.example</dd></div>
      </dl>
    </div>
    <form class="rv" id="contactForm" novalidate>
      <div class="field"><label for="f-name">お名前</label><input id="f-name" name="name" type="text" autocomplete="name" required></div>
      <div class="field"><label for="f-mail">メールアドレス</label><input id="f-mail" name="mail" type="email" autocomplete="email" required></div>
      <div class="field"><label for="f-type">ご相談の種類</label>
        <select id="f-type" name="type">
          <option>システム刷新・連携</option>
          <option>クラウド移行・運用</option>
          <option>データ活用・AI</option>
          <option>セキュリティ</option>
          <option>その他</option>
        </select>
      </div>
      <div class="field"><label for="f-msg">ご相談内容</label><textarea id="f-msg" name="msg" required></textarea></div>
      <button class="btn" type="submit">送信する <span class="arr" aria-hidden="true">→</span></button>
      <p class="status" id="status" role="status" aria-live="polite"></p>
    </form>
  </div>
</section>
</main>

<footer>
  <div class="wrap">
    <small>© 2026 Tsumugi Technologies Inc.</small>
    <span>このサイトは架空の企業を題材にしたデモです。掲載の社名・数値・住所はすべて架空のものです。</span>
  </div>
</footer>
<div class="toast" id="toast" role="status" aria-live="polite"></div>

<script>
(function(){
  var doc=document.documentElement;
  doc.classList.add('js');
  var reduce=matchMedia('(prefers-reduced-motion: reduce)').matches;
  function $(s,r){return (r||document).querySelector(s)}
  function $$(s,r){return Array.prototype.slice.call((r||document).querySelectorAll(s))}
  function clamp(v,a,b){return Math.min(b,Math.max(a,v))}

  /* --- headline: split into characters --- */
  var h1=$('#headline'), idx=0;
  function split(node){
    Array.prototype.slice.call(node.childNodes).forEach(function(n){
      if(n.nodeType===3){
        var frag=document.createDocumentFragment();
        Array.from(n.textContent).forEach(function(c){
          var s=document.createElement('span');
          s.className='ch'; s.setAttribute('aria-hidden','true');
          s.style.setProperty('--i',idx++); s.textContent=c; frag.appendChild(s);
        });
        node.replaceChild(frag,n);
      }else if(n.nodeType===1&&n.nodeName!=='BR'){split(n)}
    });
  }
  if(!reduce) split(h1);

  /* --- marquee --- */
  var techs=['Kubernetes','PostgreSQL','Rust','TypeScript','Terraform','Apache Kafka','Go','dbt','OpenTelemetry','Python','Zero Trust','gRPC'];
  var track=$('#track'), group='';
  techs.forEach(function(t){group+='<span>'+t+'</span>'});
  track.innerHTML=group+group;

  /* --- loom (one thread, woven back and forth) --- */
  var loom=$('#loom');
  if(loom){
    var s='',i,x;
    for(i=0;i<11;i++){x=22+i*36;s+='<line class="warp" x1="'+x+'" y1="10" x2="'+x+'" y2="310"/>'}
    var d='M22 34', x0=22, rows=6;
    for(var r=0;r<rows;r++){
      var y=34+r*50, dir=r%2===0?1:-1;
      for(var k=0;k<10;k++){
        var x1=x0+dir*36, cx=x0+dir*18, amp=((k+r)%2)?-14:14;
        d+=' Q'+cx+' '+(y+amp)+' '+x1+' '+y; x0=x1;
      }
      if(r<rows-1){var ny=y+50;d+=' C'+(x0+dir*26)+' '+y+' '+(x0+dir*26)+' '+ny+' '+x0+' '+ny}
    }
    s+='<path id="threadPath" class="thread dr" pathLength="1" d="'+d+'"/>';
    if(!reduce){
      s+='<circle class="shuttle" r="5"><animateMotion dur="14s" repeatCount="indefinite"><mpath href="#threadPath"/></animateMotion></circle>';
    }
    loom.innerHTML=s;
  }

  /* --- waves in careers band --- */
  function wave(amp,period,phase,y){
    var p='M0 '+y.toFixed(1);
    for(var x=0;x<=2400;x+=10){p+=' L'+x+' '+(y+amp*Math.sin(2*Math.PI*(x+phase)/period)).toFixed(1)}
    return p;
  }
  $('#wavesA').innerHTML='<path d="'+wave(16,150,0,60)+'"/><path d="'+wave(10,150,40,84)+'"/>';
  $('#wavesB').innerHTML='<path d="'+wave(22,300,20,70)+'"/>';

  /* --- reveal on scroll --- */
  $$('[data-stagger]').forEach(function(p){
    Array.prototype.forEach.call(p.children,function(c,i){c.style.setProperty('--d',(i*90)+'ms')});
  });
  var vh=innerHeight;
  if(!reduce){
    $$('.rv').forEach(function(el){if(el.getBoundingClientRect().top>vh*0.95)el.classList.add('pre')});
  }
  function runCounters(root){
    if(reduce)return;
    $$('.num',root).forEach(function(el){
      var t=parseFloat(el.getAttribute('data-target')), dec=parseInt(el.getAttribute('data-dec')||'0',10), t0=performance.now();
      (function tick(now){
        var p=clamp((now-t0)/1500,0,1), e=1-Math.pow(1-p,3);
        el.textContent=(t*e).toLocaleString('en-US',{minimumFractionDigits:dec,maximumFractionDigits:dec});
        if(p<1)requestAnimationFrame(tick);
      })(t0);
    });
  }
  var io=new IntersectionObserver(function(es){
    es.forEach(function(e){
      if(!e.isIntersecting)return;
      var el=e.target;
      el.classList.remove('pre'); el.classList.add('in');
      if(el.classList.contains('stats'))runCounters(el);
      io.unobserve(el);
    });
  },{threshold:.15,rootMargin:'0px 0px -6% 0px'});
  $$('.rv,.draw').forEach(function(t){io.observe(t)});

  /* --- scroll progress + process line --- */
  var prog=$('#progress'), steps=$('#steps'), fill=$('#fill'), stepEls=$$('.step'), ticking=false;
  function update(){
    ticking=false;
    var h=doc.scrollHeight-innerHeight;
    prog.style.transform='scaleX('+(h>0?clamp(scrollY/h,0,1):0)+')';
    if(steps){
      var r=steps.getBoundingClientRect(), line=innerHeight*0.62;
      fill.style.transform='scaleY('+clamp((line-r.top)/r.height,0,1)+')';
      stepEls.forEach(function(st){
        var b=$('.dot',st).getBoundingClientRect();
        st.classList.toggle('on',b.top+b.height/2<line);
      });
    }
  }
  addEventListener('scroll',function(){if(!ticking){ticking=true;requestAnimationFrame(update)}},{passive:true});
  addEventListener('resize',update);
  update();

  /* --- hero canvas: drifting nodes joined by threads --- */
  var hero=$('.hero'), cv=$('#weave'), ctx=cv.getContext('2d');
  var W=0,H=0,dpr=1,nodes=[],mouse={x:-9999,y:-9999,on:false},col={a:'#3FD0DA',w:'#F2B134'},frame=0,running=false,visible=true,LINK=130,PULL=170;
  function readCol(){
    var cs=getComputedStyle(doc);
    col.a=cs.getPropertyValue('--accent').trim()||col.a;
    col.w=cs.getPropertyValue('--warm').trim()||col.w;
  }
  function mk(){
    return {x:Math.random()*W,y:Math.random()*H,vx:(Math.random()-.5)*.5,vy:(Math.random()-.5)*.5,r:1.2+Math.random()*1.4,w:Math.random()<.12};
  }
  function draw(){
    ctx.clearRect(0,0,W,H);
    ctx.lineWidth=1;
    var i,j,a,b,dx,dy,d;
    ctx.strokeStyle=col.a;
    for(i=0;i<nodes.length;i++){
      a=nodes[i];
      for(j=i+1;j<nodes.length;j++){
        b=nodes[j]; dx=a.x-b.x; dy=a.y-b.y; d=Math.sqrt(dx*dx+dy*dy);
        if(d<LINK){ctx.globalAlpha=(1-d/LINK)*.5;ctx.beginPath();ctx.moveTo(a.x,a.y);ctx.lineTo(b.x,b.y);ctx.stroke()}
      }
    }
    if(mouse.on){
      ctx.strokeStyle=col.w;
      for(i=0;i<nodes.length;i++){
        a=nodes[i]; dx=a.x-mouse.x; dy=a.y-mouse.y; d=Math.sqrt(dx*dx+dy*dy);
        if(d<PULL){ctx.globalAlpha=(1-d/PULL)*.85;ctx.beginPath();ctx.moveTo(a.x,a.y);ctx.lineTo(mouse.x,mouse.y);ctx.stroke()}
      }
    }
    ctx.globalAlpha=.9;
    for(i=0;i<nodes.length;i++){
      a=nodes[i]; ctx.fillStyle=a.w?col.w:col.a; ctx.beginPath(); ctx.arc(a.x,a.y,a.r,0,6.2832); ctx.fill();
    }
    ctx.globalAlpha=1;
  }
  function stepNodes(){
    nodes.forEach(function(p){
      p.x+=p.vx; p.y+=p.vy;
      if(p.x<0||p.x>W)p.vx*=-1;
      if(p.y<0||p.y>H)p.vy*=-1;
      if(mouse.on){
        var dx=mouse.x-p.x, dy=mouse.y-p.y, d=Math.sqrt(dx*dx+dy*dy);
        if(d<PULL&&d>1){p.x+=dx/d*.35;p.y+=dy/d*.35}
      }
    });
  }
  function loop(){
    if(!running)return;
    if(!visible||document.hidden){running=false;return}
    frame++; if(frame%90===0)readCol();
    stepNodes(); draw();
    requestAnimationFrame(loop);
  }
  function start(){if(reduce||running)return;running=true;requestAnimationFrame(loop)}
  function resize(){
    var r=hero.getBoundingClientRect();
    W=r.width;H=r.height;dpr=Math.min(devicePixelRatio||1,2);
    cv.width=Math.round(W*dpr);cv.height=Math.round(H*dpr);
    cv.style.width=W+'px';cv.style.height=H+'px';
    ctx.setTransform(dpr,0,0,dpr,0,0);
    var n=clamp(Math.round(W*H/17000),26,84);
    while(nodes.length<n)nodes.push(mk());
    nodes.length=n;
    nodes.forEach(function(p){p.x=clamp(p.x,0,W);p.y=clamp(p.y,0,H)});
    draw();
  }
  readCol(); resize();
  addEventListener('resize',resize);
  hero.addEventListener('pointermove',function(e){
    var r=hero.getBoundingClientRect();
    mouse.x=e.clientX-r.left; mouse.y=e.clientY-r.top; mouse.on=true;
    hero.style.setProperty('--mx',mouse.x+'px'); hero.style.setProperty('--my',mouse.y+'px');
    if(reduce)draw();
  });
  hero.addEventListener('pointerleave',function(){mouse.on=false;if(reduce)draw()});
  new IntersectionObserver(function(es){visible=es[0].isIntersecting;if(visible)start()}).observe(hero);
  document.addEventListener('visibilitychange',function(){if(!document.hidden)start()});
  new MutationObserver(function(){readCol();draw()}).observe(doc,{attributes:true,attributeFilter:['data-theme']});
  matchMedia('(prefers-color-scheme: dark)').addEventListener('change',function(){readCol();draw()});
  start();

  /* --- tilt on work cards --- */
  if(matchMedia('(hover:hover)').matches&&!reduce){
    $$('.work-in').forEach(function(c){
      c.addEventListener('pointermove',function(e){
        var r=c.getBoundingClientRect(), px=(e.clientX-r.left)/r.width-.5, py=(e.clientY-r.top)/r.height-.5;
        c.style.transform='perspective(900px) rotateY('+(px*7).toFixed(2)+'deg) rotateX('+(-py*7).toFixed(2)+'deg)';
      });
      c.addEventListener('pointerleave',function(){c.style.transform=''});
    });
  }

  /* --- menu --- */
  var hdr=$('header.site'), btn=$('.menu');
  btn.addEventListener('click',function(){
    var o=hdr.classList.toggle('open'); btn.setAttribute('aria-expanded',o?'true':'false');
  });
  $$('#nav a').forEach(function(a){a.addEventListener('click',function(){hdr.classList.remove('open');btn.setAttribute('aria-expanded','false')})});

  /* --- toast / demo links / form --- */
  var toast=$('#toast'), tt;
  function showToast(msg){
    toast.textContent=msg; toast.classList.add('show'); clearTimeout(tt);
    tt=setTimeout(function(){toast.classList.remove('show')},2600);
  }
  $$('a[data-demo]').forEach(function(a){
    a.addEventListener('click',function(e){e.preventDefault();showToast('架空のサイトのため、リンク先はありません。')});
  });
  var form=$('#contactForm'), status=$('#status');
  form.addEventListener('submit',function(e){
    e.preventDefault();
    if(!form.checkValidity()){form.reportValidity();status.textContent='未入力の項目があります。';return}
    status.textContent='入力を確認しました。架空のサイトのため、内容はどこにも送信されません。';
    form.reset();
  });
})();
</script>
</body>
</html>
