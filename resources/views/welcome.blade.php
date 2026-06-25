<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPAMS — Smart Inventory & POS Management</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600,700,800,900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --teal:   #0d9488;
            --teal-d: #0f766e;
            --teal-l: #ccfbf1;
            --dark:   #0e2a38;
            --dark2:  #1a3e52;
            --gray:   #6b7280;
            --light:  #f0fdfb;
        }

        html { scroll-behavior: smooth; }
        body { font-family: 'Figtree', sans-serif; color: #1f2937; background: #fff; }

        /* ── NAV ── */
        nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 50;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 5%; height: 68px;
            background: rgba(255,255,255,.9);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(13,148,136,.15);
            transition: box-shadow .3s;
        }
        nav.scrolled { box-shadow: 0 2px 20px rgba(0,0,0,.08); }
        .nav-brand { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .nav-logo {
            width: 38px; height: 38px; border-radius: 10px;
            background: var(--teal); color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; font-weight: 900;
        }
        .nav-name { font-size: 20px; font-weight: 800; color: var(--dark); letter-spacing: -.3px; }
        .nav-name span { color: var(--teal); }
        .nav-links { display: flex; align-items: center; gap: 8px; }
        .btn-ghost {
            padding: 8px 20px; border-radius: 8px; font-size: 14px; font-weight: 600;
            color: var(--dark); text-decoration: none; transition: background .2s;
        }
        .btn-ghost:hover { background: var(--light); }
        .btn-primary {
            padding: 9px 22px; border-radius: 8px; font-size: 14px; font-weight: 700;
            background: var(--teal); color: #fff; text-decoration: none;
            transition: background .2s, transform .15s;
        }
        .btn-primary:hover { background: var(--teal-d); transform: translateY(-1px); }

        /* ── HERO ── */
        .hero {
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            text-align: center;
            padding: 100px 5% 60px;
            background: linear-gradient(160deg, #fff 0%, var(--light) 50%, #e0f7f4 100%);
            position: relative; overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute; top: -120px; right: -120px;
            width: 500px; height: 500px; border-radius: 50%;
            background: radial-gradient(circle, rgba(13,148,136,.12) 0%, transparent 70%);
            pointer-events: none;
        }
        .hero::after {
            content: '';
            position: absolute; bottom: -80px; left: -80px;
            width: 360px; height: 360px; border-radius: 50%;
            background: radial-gradient(circle, rgba(13,148,136,.08) 0%, transparent 70%);
            pointer-events: none;
        }
        .hero-inner { max-width: 780px; position: relative; z-index: 1; }
        .hero-badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: var(--teal-l); color: var(--teal-d);
            padding: 6px 16px; border-radius: 99px;
            font-size: 13px; font-weight: 700; letter-spacing: .3px;
            margin-bottom: 28px;
        }
        .hero-badge svg { width: 14px; height: 14px; }
        h1 {
            font-size: clamp(2.4rem, 6vw, 4rem);
            font-weight: 900; line-height: 1.1;
            color: var(--dark); letter-spacing: -.5px;
            margin-bottom: 20px;
        }
        h1 .accent { color: var(--teal); }
        .hero-sub {
            font-size: 1.15rem; color: var(--gray);
            line-height: 1.7; max-width: 580px; margin: 0 auto 40px;
        }
        .hero-cta { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
        .btn-hero {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 14px 32px; border-radius: 10px; font-size: 15px;
            font-weight: 700; text-decoration: none; transition: all .2s;
        }
        .btn-hero-primary {
            background: var(--teal); color: #fff;
            box-shadow: 0 4px 20px rgba(13,148,136,.35);
        }
        .btn-hero-primary:hover { background: var(--teal-d); transform: translateY(-2px); box-shadow: 0 8px 28px rgba(13,148,136,.4); }
        .btn-hero-secondary {
            background: #fff; color: var(--dark);
            border: 1.5px solid #e2e8f0;
        }
        .btn-hero-secondary:hover { border-color: var(--teal); color: var(--teal); transform: translateY(-1px); }

        /* ── STATS ── */
        .stats {
            background: var(--dark);
            padding: 48px 5%;
        }
        .stats-grid {
            max-width: 900px; margin: 0 auto;
            display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 0; text-align: center;
        }
        .stat-item {
            padding: 20px 24px;
            border-right: 1px solid rgba(255,255,255,.08);
        }
        .stat-item:last-child { border-right: none; }
        .stat-num {
            font-size: 2.2rem; font-weight: 900;
            color: var(--teal); line-height: 1;
            margin-bottom: 6px;
        }
        .stat-label { font-size: 13px; color: #94a3b8; font-weight: 500; }

        /* ── SECTION COMMONS ── */
        section { padding: 90px 5%; }
        .section-label {
            display: inline-block;
            font-size: 11px; font-weight: 800; letter-spacing: 1.5px; text-transform: uppercase;
            color: var(--teal); margin-bottom: 12px;
        }
        .section-title {
            font-size: clamp(1.7rem, 4vw, 2.4rem);
            font-weight: 800; color: var(--dark);
            letter-spacing: -.3px; line-height: 1.2;
            margin-bottom: 14px;
        }
        .section-sub { color: var(--gray); font-size: 1rem; line-height: 1.7; max-width: 540px; }
        .section-header { margin-bottom: 56px; }

        /* ── FEATURES ── */
        .features { background: #fff; }
        .features-grid {
            max-width: 1100px; margin: 0 auto;
            display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
        }
        .feature-card {
            background: #fff; border: 1.5px solid #e5e7eb;
            border-radius: 16px; padding: 28px;
            transition: border-color .25s, box-shadow .25s, transform .25s;
        }
        .feature-card:hover {
            border-color: var(--teal);
            box-shadow: 0 8px 32px rgba(13,148,136,.12);
            transform: translateY(-4px);
        }
        .feature-icon {
            width: 48px; height: 48px; border-radius: 12px;
            background: var(--teal-l);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 18px;
        }
        .feature-icon svg { width: 24px; height: 24px; color: var(--teal); stroke: var(--teal); }
        .feature-title { font-size: 1.05rem; font-weight: 700; color: var(--dark); margin-bottom: 8px; }
        .feature-desc { font-size: .9rem; color: var(--gray); line-height: 1.65; }

        /* ── HOW IT WORKS ── */
        .how { background: var(--light); }
        .how-inner { max-width: 1100px; margin: 0 auto; display: grid; grid-template-columns: 1fr 1fr; gap: 64px; align-items: center; }
        .how-steps { display: flex; flex-direction: column; gap: 28px; }
        .how-step { display: flex; gap: 18px; align-items: flex-start; }
        .step-num {
            flex-shrink: 0; width: 38px; height: 38px; border-radius: 10px;
            background: var(--teal); color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: 15px; font-weight: 800;
        }
        .step-content h3 { font-size: 1rem; font-weight: 700; color: var(--dark); margin-bottom: 4px; }
        .step-content p  { font-size: .875rem; color: var(--gray); line-height: 1.6; }
        .how-visual {
            background: var(--dark); border-radius: 20px;
            padding: 28px; box-shadow: 0 20px 60px rgba(0,0,0,.18);
        }
        .mock-bar {
            display: flex; align-items: center; gap: 8px; margin-bottom: 20px;
        }
        .mock-dot { width: 10px; height: 10px; border-radius: 50%; }
        .mock-title { color: #94a3b8; font-size: 12px; font-weight: 600; margin-left: auto; }
        .mock-card {
            background: var(--dark2); border-radius: 10px;
            padding: 14px; margin-bottom: 10px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .mock-card-left { display: flex; align-items: center; gap: 10px; }
        .mock-card-icon { width: 32px; height: 32px; border-radius: 8px; background: rgba(13,148,136,.3); display: flex; align-items: center; justify-content: center; }
        .mock-card-label { font-size: 12px; color: #e2e8f0; font-weight: 600; }
        .mock-card-sub   { font-size: 10px; color: #64748b; }
        .mock-card-val   { font-size: 13px; font-weight: 800; color: var(--teal); }
        .mock-chart-bars { display: flex; align-items: flex-end; gap: 6px; height: 60px; margin-top: 16px; }
        .mock-bar-item { flex: 1; background: rgba(13,148,136,.25); border-radius: 4px 4px 0 0; transition: background .3s; }
        .mock-bar-item.active { background: var(--teal); }

        /* ── MODULES ── */
        .modules { background: #fff; }
        .modules-inner { max-width: 1100px; margin: 0 auto; }
        .modules-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
        }
        .module-pill {
            display: flex; align-items: center; gap: 12px;
            padding: 16px 20px; border-radius: 12px;
            border: 1.5px solid #e5e7eb;
            text-decoration: none; transition: all .2s;
        }
        .module-pill:hover { border-color: var(--teal); background: var(--light); }
        .module-pill-icon { width: 36px; height: 36px; border-radius: 8px; background: var(--teal-l); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .module-pill-icon svg { width: 18px; height: 18px; stroke: var(--teal); }
        .module-pill-text { font-size: .9rem; font-weight: 600; color: var(--dark); }

        /* ── CTA BANNER ── */
        .cta-banner {
            background: linear-gradient(135deg, var(--dark) 0%, var(--dark2) 100%);
            padding: 80px 5%; text-align: center;
        }
        .cta-banner h2 { font-size: clamp(1.6rem, 4vw, 2.4rem); font-weight: 800; color: #fff; margin-bottom: 14px; }
        .cta-banner p { color: #94a3b8; font-size: 1rem; margin-bottom: 36px; max-width: 480px; margin-left: auto; margin-right: auto; }

        /* ── FOOTER ── */
        footer {
            background: var(--dark); border-top: 1px solid rgba(255,255,255,.06);
            padding: 40px 5% 28px;
        }
        .footer-inner {
            max-width: 1100px; margin: 0 auto;
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 16px;
        }
        .footer-brand { display: flex; align-items: center; gap: 10px; }
        .footer-logo { width: 32px; height: 32px; border-radius: 8px; background: var(--teal); display: flex; align-items: center; justify-content: center; font-size: 15px; font-weight: 900; color: #fff; }
        .footer-name { font-size: 16px; font-weight: 800; color: #fff; }
        .footer-copy { font-size: 13px; color: #475569; }
        .footer-links { display: flex; gap: 20px; }
        .footer-links a { font-size: 13px; color: #64748b; text-decoration: none; transition: color .2s; }
        .footer-links a:hover { color: var(--teal); }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .how-inner { grid-template-columns: 1fr; }
            .how-visual { display: none; }
            .nav-links .btn-ghost { display: none; }
            .stat-item { border-right: none; border-bottom: 1px solid rgba(255,255,255,.08); }
            .stat-item:last-child { border-bottom: none; }
        }
    </style>
</head>
<body>

<!-- NAV -->
<nav id="navbar">
    <a href="/" class="nav-brand">
        <div class="nav-logo">S</div>
        <span class="nav-name">SIP<span>AMS</span></span>
    </a>
    <div class="nav-links">
        <a href="#features" class="btn-ghost">Features</a>
        <a href="#modules" class="btn-ghost">Modules</a>
        <a href="{{ route('login') }}" class="btn-ghost">Log in</a>
        <a href="{{ route('login') }}" class="btn-primary">Get Started →</a>
    </div>
</nav>

<!-- HERO -->
<section class="hero">
    <div class="hero-inner">
        <div class="hero-badge">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            Smart Inventory &amp; POS Platform
        </div>
        <h1>Run Your Business<br>with <span class="accent">Confidence</span></h1>
        <p class="hero-sub">
            SIPAMS gives you a powerful Point of Sale, real-time inventory tracking, purchase management, and detailed reports — all in one clean, fast system.
        </p>
        <div class="hero-cta">
            <a href="{{ route('login') }}" class="btn-hero btn-hero-primary">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                Sign In to Dashboard
            </a>
            <a href="#features" class="btn-hero btn-hero-secondary">
                Explore Features
            </a>
        </div>
    </div>
</section>

<!-- STATS -->
<div class="stats">
    <div class="stats-grid">
        <div class="stat-item">
            <div class="stat-num">POS</div>
            <div class="stat-label">Point of Sale Terminal</div>
        </div>
        <div class="stat-item">
            <div class="stat-num">360°</div>
            <div class="stat-label">Inventory Visibility</div>
        </div>
        <div class="stat-item">
            <div class="stat-num">8+</div>
            <div class="stat-label">Report Types</div>
        </div>
        <div class="stat-item">
            <div class="stat-num">Real‑time</div>
            <div class="stat-label">Stock Updates</div>
        </div>
    </div>
</div>

<!-- FEATURES -->
<section class="features" id="features">
    <div style="max-width:1100px;margin:0 auto;">
        <div class="section-header">
            <span class="section-label">What You Get</span>
            <h2 class="section-title">Everything your business needs</h2>
            <p class="section-sub">From ringing up a sale to tracking every stock movement — SIPAMS has you covered.</p>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                </div>
                <div class="feature-title">Point of Sale</div>
                <div class="feature-desc">Fast, intuitive checkout with barcode scanning, multiple payment methods, discounts, and instant receipts.</div>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <div class="feature-title">Inventory Management</div>
                <div class="feature-desc">Track stock levels in real-time, set reorder alerts, manage batches, and view full movement history.</div>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div class="feature-title">Purchase Orders</div>
                <div class="feature-desc">Create and track purchase orders from suppliers, manage payments, and generate professional PO PDFs.</div>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <div class="feature-title">Advanced Reports</div>
                <div class="feature-desc">Sales, inventory, financial, profit &amp; loss, product movement, and audit trail reports with export options.</div>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                </div>
                <div class="feature-title">Barcode Label Printing</div>
                <div class="feature-desc">Search or scan products, set quantities, and print professional barcode labels in multiple paper formats.</div>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="feature-title">Finance & Accounting</div>
                <div class="feature-desc">Track expenses by category, view profit &amp; loss statements, and maintain a complete financial picture.</div>
            </div>
        </div>
    </div>
</section>

<!-- HOW IT WORKS -->
<section class="how">
    <div class="how-inner">
        <div>
            <span class="section-label">How It Works</span>
            <h2 class="section-title">Simple by design,<br>powerful by nature</h2>
            <p class="section-sub" style="margin-bottom:36px;">SIPAMS is built for business owners and staff who need speed, not complexity.</p>
            <div class="how-steps">
                <div class="how-step">
                    <div class="step-num">1</div>
                    <div class="step-content">
                        <h3>Add your products &amp; suppliers</h3>
                        <p>Set up your catalog with categories, SKUs, barcodes, and pricing. Link products to suppliers for seamless procurement.</p>
                    </div>
                </div>
                <div class="how-step">
                    <div class="step-num">2</div>
                    <div class="step-content">
                        <h3>Sell at the POS terminal</h3>
                        <p>Ring up sales by scanning barcodes or searching by name. Accept cash or other payment methods and print receipts instantly.</p>
                    </div>
                </div>
                <div class="how-step">
                    <div class="step-num">3</div>
                    <div class="step-content">
                        <h3>Restock via purchase orders</h3>
                        <p>Create POs for your suppliers, track delivery status, and stock levels update automatically when orders are received.</p>
                    </div>
                </div>
                <div class="how-step">
                    <div class="step-num">4</div>
                    <div class="step-content">
                        <h3>Review reports &amp; grow</h3>
                        <p>Use real-time dashboards and detailed reports to understand sales trends, top products, and your financial position.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="how-visual">
            <div class="mock-bar">
                <div class="mock-dot" style="background:#ef4444;"></div>
                <div class="mock-dot" style="background:#f59e0b;margin-left:4px;"></div>
                <div class="mock-dot" style="background:#10b981;margin-left:4px;"></div>
                <span class="mock-title">Dashboard Overview</span>
            </div>
            <div class="mock-card">
                <div class="mock-card-left">
                    <div class="mock-card-icon"><svg width="16" height="16" fill="none" stroke="#0d9488" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg></div>
                    <div><div class="mock-card-label">Today's Sales</div><div class="mock-card-sub">32 transactions</div></div>
                </div>
                <div class="mock-card-val">GHS 4,820</div>
            </div>
            <div class="mock-card">
                <div class="mock-card-left">
                    <div class="mock-card-icon"><svg width="16" height="16" fill="none" stroke="#0d9488" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg></div>
                    <div><div class="mock-card-label">Stock Value</div><div class="mock-card-sub">248 products</div></div>
                </div>
                <div class="mock-card-val">GHS 91,340</div>
            </div>
            <div class="mock-card">
                <div class="mock-card-left">
                    <div class="mock-card-icon"><svg width="16" height="16" fill="none" stroke="#f59e0b" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.07 19H19a2 2 0 001.75-2.97l-6.98-12a2 2 0 00-3.5 0l-6.98 12A2 2 0 005.07 19z"/></svg></div>
                    <div><div class="mock-card-label">Low Stock</div><div class="mock-card-sub">Needs attention</div></div>
                </div>
                <div class="mock-card-val" style="color:#f59e0b;">12 items</div>
            </div>
            <div class="mock-chart-bars">
                <div class="mock-bar-item" style="height:40%;"></div>
                <div class="mock-bar-item" style="height:65%;"></div>
                <div class="mock-bar-item active" style="height:80%;"></div>
                <div class="mock-bar-item" style="height:55%;"></div>
                <div class="mock-bar-item active" style="height:90%;"></div>
                <div class="mock-bar-item" style="height:70%;"></div>
                <div class="mock-bar-item active" style="height:100%;"></div>
            </div>
        </div>
    </div>
</section>

<!-- MODULES -->
<section class="modules" id="modules">
    <div class="modules-inner">
        <div class="section-header">
            <span class="section-label">All Modules</span>
            <h2 class="section-title">One system, complete control</h2>
            <p class="section-sub">Every module works together seamlessly so your data is always in sync.</p>
        </div>
        <div class="modules-grid">
            <a href="{{ route('login') }}" class="module-pill">
                <div class="module-pill-icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg></div>
                <span class="module-pill-text">Dashboard</span>
            </a>
            <a href="{{ route('login') }}" class="module-pill">
                <div class="module-pill-icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg></div>
                <span class="module-pill-text">Point of Sale</span>
            </a>
            <a href="{{ route('login') }}" class="module-pill">
                <div class="module-pill-icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg></div>
                <span class="module-pill-text">Categories</span>
            </a>
            <a href="{{ route('login') }}" class="module-pill">
                <div class="module-pill-icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg></div>
                <span class="module-pill-text">Products</span>
            </a>
            <a href="{{ route('login') }}" class="module-pill">
                <div class="module-pill-icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg></div>
                <span class="module-pill-text">Inventory</span>
            </a>
            <a href="{{ route('login') }}" class="module-pill">
                <div class="module-pill-icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></div>
                <span class="module-pill-text">Suppliers</span>
            </a>
            <a href="{{ route('login') }}" class="module-pill">
                <div class="module-pill-icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
                <span class="module-pill-text">Purchases</span>
            </a>
            <a href="{{ route('login') }}" class="module-pill">
                <div class="module-pill-icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a4 4 0 014 4v1m-7-9l-3 3 3 3"/></svg></div>
                <span class="module-pill-text">Sales Returns</span>
            </a>
            <a href="{{ route('login') }}" class="module-pill">
                <div class="module-pill-icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg></div>
                <span class="module-pill-text">Expenses</span>
            </a>
            <a href="{{ route('login') }}" class="module-pill">
                <div class="module-pill-icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg></div>
                <span class="module-pill-text">Reports</span>
            </a>
            <a href="{{ route('login') }}" class="module-pill">
                <div class="module-pill-icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg></div>
                <span class="module-pill-text">Print Labels</span>
            </a>
            <a href="{{ route('login') }}" class="module-pill">
                <div class="module-pill-icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
                <span class="module-pill-text">Administration</span>
            </a>
        </div>
    </div>
</section>

<!-- CTA BANNER -->
<div class="cta-banner">
    <h2>Ready to take control of your business?</h2>
    <p>Sign in to your SIPAMS dashboard and start managing sales, stock, and finances with clarity.</p>
    <a href="{{ route('login') }}" class="btn-hero btn-hero-primary" style="display:inline-flex;">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
        Sign In Now
    </a>
</div>

<!-- FOOTER -->
<footer>
    <div class="footer-inner">
        <div class="footer-brand">
            <div class="footer-logo">S</div>
            <span class="footer-name">SIPAMS</span>
        </div>
        <span class="footer-copy">© {{ date('Y') }} SIPAMS. Smart Inventory & POS Management.</span>
        <div class="footer-links">
            <a href="{{ route('login') }}">Login</a>
            <a href="#features">Features</a>
            <a href="#modules">Modules</a>
        </div>
    </div>
</footer>

<script>
    // Navbar scroll shadow
    const nav = document.getElementById('navbar');
    window.addEventListener('scroll', () => {
        nav.classList.toggle('scrolled', window.scrollY > 10);
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(a => {
        a.addEventListener('click', e => {
            const target = document.querySelector(a.getAttribute('href'));
            if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth' }); }
        });
    });
</script>
</body>
</html>
