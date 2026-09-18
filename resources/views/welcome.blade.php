<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="BackendApp API workspace for authentication and product management.">

        <title>BackendApp API</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <style>
            :root {
                --ink: #172321;
                --muted: #64736e;
                --paper: #f4f6f0;
                --surface: #ffffff;
                --line: #dce4dc;
                --mint: #c8e8d5;
                --green: #166b50;
                --green-dark: #0d4938;
                --coral: #e5795f;
                --mono: "DM Mono", monospace;
                --sans: "Manrope", sans-serif;
            }

            * { box-sizing: border-box; }
            html { scroll-behavior: smooth; }
            body { margin: 0; color: var(--ink); background: var(--paper); font-family: var(--sans); -webkit-font-smoothing: antialiased; }
            a { color: inherit; text-decoration: none; }
            .shell { min-height: 100vh; overflow: hidden; background: linear-gradient(135deg, rgba(200, 232, 213, .42), transparent 32%), radial-gradient(circle at 87% 11%, rgba(229, 121, 95, .14), transparent 24%), var(--paper); }
            .container { width: min(1160px, calc(100% - 48px)); margin: 0 auto; }
            .nav { display: flex; align-items: center; justify-content: space-between; padding: 27px 0; border-bottom: 1px solid rgba(23, 35, 33, .1); }
            .brand { display: inline-flex; align-items: center; gap: 11px; font-weight: 800; letter-spacing: -.04em; }
            .brand-mark { display: grid; place-items: center; width: 33px; height: 33px; color: white; background: var(--green); border-radius: 9px; font-family: var(--mono); font-size: 14px; }
            .brand-name { font-size: 17px; }
            .brand-name span { color: var(--green); }
            .nav-link, .button { font-size: 13px; font-weight: 700; }
            .nav-link { color: var(--muted); }
            .nav-link:hover { color: var(--green); }
            .hero { display: grid; grid-template-columns: minmax(0, 1.1fr) minmax(300px, .9fr); gap: 80px; align-items: center; padding: 92px 0 84px; }
            .eyebrow { display: flex; align-items: center; gap: 9px; margin-bottom: 25px; color: var(--green); font: 500 12px var(--mono); text-transform: uppercase; letter-spacing: .08em; }
            .eyebrow::before { width: 28px; height: 1px; background: var(--coral); content: ""; }
            h1 { max-width: 660px; margin: 0; font-size: clamp(44px, 7vw, 82px); line-height: .98; letter-spacing: -.075em; }
            h1 em { color: var(--green); font-style: normal; }
            .intro { max-width: 540px; margin: 26px 0 33px; color: var(--muted); font-size: 17px; line-height: 1.7; }
            .actions { display: flex; flex-wrap: wrap; gap: 12px; }
            .button { display: inline-flex; align-items: center; gap: 10px; padding: 14px 19px; border: 1px solid var(--green); border-radius: 8px; transition: transform .2s ease, box-shadow .2s ease; }
            .button:hover { transform: translateY(-2px); box-shadow: 0 10px 22px rgba(13, 73, 56, .14); }
            .button-primary { color: white; background: var(--green); }
            .button-secondary { color: var(--green-dark); background: transparent; border-color: var(--line); }
            .button-arrow { font-size: 17px; line-height: 1; }
            .terminal { position: relative; padding: 22px; color: #d9f5e1; background: var(--green-dark); border-radius: 14px; box-shadow: 19px 22px 0 rgba(200, 232, 213, .65); font: 13px/1.8 var(--mono); transform: rotate(1.5deg); }
            .terminal::before { display: block; width: 7px; height: 7px; margin-bottom: 21px; background: var(--coral); border-radius: 50%; box-shadow: 15px 0 #e7c667, 30px 0 #8ac6a1; content: ""; }
            .terminal .dim { color: #8db7a2; }
            .terminal .accent { color: #f6c8a5; }
            .terminal .cursor { display: inline-block; width: 7px; height: 15px; margin-left: 4px; vertical-align: -2px; background: #c8e8d5; animation: blink 1.1s steps(2, jump-none) infinite; }
            @keyframes blink { 50% { opacity: 0; } }
            .section { padding: 28px 0 94px; }
            .section-heading { display: flex; align-items: end; justify-content: space-between; gap: 24px; margin-bottom: 24px; }
            h2 { margin: 0; font-size: 27px; letter-spacing: -.05em; }
            .section-heading p { margin: 0; color: var(--muted); font-size: 13px; }
            .grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; }
            .card { min-height: 190px; padding: 24px; background: rgba(255, 255, 255, .72); border: 1px solid var(--line); border-radius: 10px; }
            .card-number { color: var(--coral); font: 500 12px var(--mono); }
            .card h3 { margin: 31px 0 9px; font-size: 18px; letter-spacing: -.04em; }
            .card p { margin: 0; color: var(--muted); font-size: 13px; line-height: 1.65; }
            .code { display: inline-block; margin-top: 18px; padding: 5px 7px; color: var(--green); background: var(--mint); border-radius: 4px; font: 11px var(--mono); }
            .flow { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0; margin-top: 56px; border-top: 1px solid var(--line); }
            .flow-step { padding: 22px 22px 0 0; border-right: 1px solid var(--line); }
            .flow-step + .flow-step { padding-left: 22px; }
            .flow-step:last-child { border-right: 0; }
            .flow-step strong { display: block; margin-bottom: 7px; font-size: 14px; }
            .flow-step span { color: var(--muted); font: 12px/1.6 var(--mono); }
            footer { padding: 20px 0 36px; color: var(--muted); border-top: 1px solid var(--line); font-size: 12px; }
            footer .container { display: flex; justify-content: space-between; gap: 20px; }
            @media (max-width: 760px) {
                .container { width: min(100% - 32px, 560px); }
                .hero { grid-template-columns: 1fr; gap: 50px; padding: 62px 0 70px; }
                .terminal { max-width: 500px; margin: 0 auto; transform: rotate(0); }
                .grid, .flow { grid-template-columns: 1fr; }
                .flow { gap: 18px; }
                .flow-step, .flow-step + .flow-step { padding: 18px 0 0; border-right: 0; border-top: 1px solid var(--line); }
                .flow-step:first-child { border-top: 0; }
                footer .container { align-items: flex-start; flex-direction: column; }
            }
        </style>
    </head>
    <body>
        <div class="shell">
            <div class="container">
                <nav class="nav" aria-label="Main navigation">
                    <a class="brand" href="{{ url('/') }}" aria-label="BackendApp home">
                        <span class="brand-mark">/&gt;</span>
                        <span class="brand-name">backend<span>app</span></span>
                    </a>
                    <a class="nav-link" href="{{ url('/api/documentation') }}">API documentation <span aria-hidden="true">↗</span></a>
                </nav>

                <main>
                    <section class="hero">
                        <div>
                            <div class="eyebrow">Laravel API workspace</div>
                            <h1>Build on a backend that stays <em>clear.</em></h1>
                            <p class="intro">A focused API foundation for authentication and product management. Explore the endpoints, issue a token, and get moving.</p>
                            <div class="actions">
                                <a class="button button-primary" href="{{ url('/api/documentation') }}">Open Swagger <span class="button-arrow" aria-hidden="true">↗</span></a>
                                <a class="button button-secondary" href="#capabilities">View capabilities <span class="button-arrow" aria-hidden="true">↓</span></a>
                            </div>
                        </div>

                        <div class="terminal" aria-label="API request example">
                            <div><span class="dim">$</span> curl -X POST /api/login \</div>
                            <div>&nbsp;&nbsp;-H <span class="accent">"Content-Type: application/json"</span> \</div>
                            <div>&nbsp;&nbsp;-d <span class="accent">'{ "email": "user@example.com" }'</span></div>
                            <br>
                            <div><span class="dim">&lt;</span> <span class="accent">200 OK</span></div>
                            <div>{ <span class="dim">"token"</span>: <span class="accent">"sanctum-token"</span> }<span class="cursor" aria-hidden="true"></span></div>
                        </div>
                    </section>

                    <section class="section" id="capabilities">
                        <div class="section-heading">
                            <h2>What is ready</h2>
                            <p>Small surface area. Useful primitives.</p>
                        </div>

                        <div class="grid">
                            <article class="card">
                                <div class="card-number">01 / AUTH</div>
                                <h3>Token authentication</h3>
                                <p>Sanctum-powered login flow for protected API requests.</p>
                                <span class="code">POST /api/login</span>
                            </article>
                            <article class="card">
                                <div class="card-number">02 / CATALOG</div>
                                <h3>Product management</h3>
                                <p>Create, inspect, update, and remove products through a clean REST surface.</p>
                                <span class="code">/api/products</span>
                            </article>
                            <article class="card">
                                <div class="card-number">03 / DOCS</div>
                                <h3>Interactive reference</h3>
                                <p>Try requests directly from the generated Swagger documentation.</p>
                                <span class="code">OpenAPI 3.0</span>
                            </article>
                        </div>

                        <div class="flow" aria-label="Authentication flow">
                            <div class="flow-step"><strong>01. Authenticate</strong><span>POST /api/login</span></div>
                            <div class="flow-step"><strong>02. Add your token</strong><span>Authorization: Bearer ...</span></div>
                            <div class="flow-step"><strong>03. Use the API</strong><span>GET /api/products</span></div>
                        </div>
                    </section>
                </main>

                <footer>
                    <div class="container">
                        <span>BackendApp · API foundation</span>
                        <span>Laravel {{ Illuminate\Foundation\Application::VERSION }} · PHP {{ PHP_VERSION }}</span>
                    </div>
                </footer>
            </div>
        </div>
    </body>
</html>
