@extends('layouts.app')

@section('title', 'Buyme Bargains')

@section('content')

    <main id="tt-pageContent" class="tt-offset-small">
        <div class="container">
            <div class="tt-tab-wrapper">
                <div class="tt-wrapper-inner">
                    <ul class="nav nav-tabs pt-tabs-default tabs-mobile" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link nav-link-after nav-link-mobile" data-toggle="tab" href="#about"
                                role="tab"><span class="span-mobile">About</span></a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link nav-link-after nav-link-mobile" data-toggle="tab" href="#guidelines"
                                role="tab"><span class="span-mobile">Guidelines</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-after nav-link-mobile" data-toggle="tab" href="#faq"
                                role="tab"><span class="span-mobile">FAQ</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-after nav-link-mobile" data-toggle="tab" href="#term_and_services"
                                role="tab"><span class="span-mobile">Terms of
                                    Service</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-after nav-link-mobile" data-toggle="tab" href="#privacy"
                                role="tab"><span class="span-mobile">Privacy</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-after nav-link-mobile" data-toggle="tab" href="#cookie_policy"
                                role="tab"><span class="span-mobile">Cookie Policy</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-after nav-link-mobile" data-toggle="tab" href="#contact"
                                role="tab"><span class="span-mobile">Contact
                                    Us</span></a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div class="tab-pane" id="about" role="tabpanel">
                        {!! $aboutContent ?? '' !!}
                    </div>

                    <div class="tab-pane " id="guidelines" role="tabpanel">
                        {!! $guidelinesContent ?? '' !!}
                    </div>

                    <div class="tab-pane" id="faq" role="tabpanel">
                        {!! $faqContent ?? '' !!}
                    </div>

                    <div class="tab-pane" id="term_and_services" role="tabpanel">
                        {!! $termsContent ?? '' !!}
                    </div>

                    <div class="tab-pane" id="privacy" role="tabpanel">
                        {!! $privacyContent ?? '' !!}
                    </div>
                    <div class="tab-pane" id="cookie_policy" role="tabpanel">
                        {!! '
                                                                        <div class="cookie-policy">
                                                                            <div class="container">
                                                                                <h1>Cookie Policy</h1>
                                                                                <p><strong>Last updated: June 17, 2025</strong></p>
                                                                                <p>At Buyme Bargains, we use cookies to enhance your experience on our website. This Cookie
                                                                                    Policy explains what cookies are, how we use them, and how you can manage your cookie
                                                                                    preferences.</p>
                        
                                                                                <h2>1. What Are Cookies?</h2>
                                                                                <p>Cookies are small text files stored on your device (computer, tablet, or smartphone) when
                                                                                    you visit a website. They allow websites to remember information about your visit, such
                                                                                    as your login status, preferences, and other settings, to make your browsing experience
                                                                                    more efficient and personalized.</p>

                                                                                <h2>2. How We Use Cookies</h2>
                                                                                <p>We use cookies for the following purposes:</p>
                                                                                <ul>
                                                                                    <li><strong>Essential Cookies:</strong> These are necessary for the core functionality
                                                                                        of our website, such as enabling you to log in, manage sessions, and navigate
                                                                                        securely.</li>
                                                                                    <li><strong>Performance Cookies:</strong> These help us analyze website traffic and
                                                                                        usage patterns to improve our services (e.g., through Google Analytics).</li>
                                                                                    <li><strong>Functionality Cookies:</strong> These allow us to remember your preferences,
                                                                                        such as language settings or saved filters, to provide a tailored experience.</li>
                                                                                    <li><strong>Advertising Cookies:</strong> These may be used to deliver relevant
                                                                                        advertisements through our third-party partners, based on your interests and
                                                                                        browsing behavior.</li>
                                                                                </ul>

                                                                                <h2>3. Third-Party Cookies</h2>
                                                                                <p>Some cookies are placed by third-party services that we integrate into our website,
                                                                                    including:</p>
                                                                                <ul>
                                                                                    <li>Google Analytics (for website performance analysis)</li>
                                                                                    <li>Embedded social media features (e.g., sharing buttons)</li>
                                                                                    <li>Affiliate marketing or advertising partners (for personalized ads)</li>
                                                                                </ul>
                                                                                <p>These third parties may collect and process data according to their own privacy policies,
                                                                                    which we encourage you to review.</p>

                                                                                <h2>4. Managing Cookies</h2>
                                                                                <p>You can control or delete cookies through your browser settings at any time. Most
                                                                                    browsers allow you to block cookies or set preferences for specific websites. However,
                                                                                    please note that disabling certain cookies may impact the functionality of some parts of
                                                                                    our website.</p>
                                                                                <p>For more information on managing cookies, visit <a href="https://www.allaboutcookies.org"
                                                                                        target="_blank" rel="noopener">www.allaboutcookies.org</a>.</p>

                                                                                <h2>5. Updates to This Policy</h2>
                                                                                <p>We may update this Cookie Policy periodically to reflect changes in our practices or
                                                                                    legal requirements. Any updates will be posted on this page with a revised "Last
                                                                                    updated" date.</p>

                                                                                <h2>6. Contact Us</h2>
                                                                                <p>If you have any questions or concerns about our Cookie Policy, please reach out to us via
                                                                                    our <a href="pages/?tab=contact">Contact Us</a> page.</p>
                                                                            </div>
                                                                        </div>
                                                      ' !!}
                    </div>
                    <div class="tab-pane" id="contact" role="tabpanel">
                        {!! $contactContent ?? '' !!}
                    </div>
                </div>

            </div>
        </div>
    </main>

    <style>
        .nav-link-after::after {
            background: none;
        }

        .cookie-policy {
            padding: 40px 20px;
            background: #f8f9fa;
            min-height: calc(100vh - 200px);
        }

        .cookie-policy h1 {
            font-size: 2.5rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
            padding-left: 10px;
        }

        .cookie-policy h2 {
            font-size: 1.8rem;
            font-weight: 600;
            color: #333;
            margin-top: 30px;
            margin-bottom: 15px;
        }

        .cookie-policy p,
        .cookie-policy ul {
            font-size: 1rem;
            color: #666;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .cookie-policy ul {
            padding-left: 20px;
        }

        .cookie-policy li {
            margin-bottom: 10px;
        }

        .cookie-policy a {
            color: #28a745;
            text-decoration: none;
            font-weight: bold;
        }

        .cookie-policy a:hover {
            text-decoration: underline;
        }

        @media(max-width:426px) {
            .nav-item-custom {
                width: 100%;
                padding-right: 0px !important;
            }

            .nav-item-button-custom {
                width: 100%;
            }

            .nav-link-mobile {
                font-size: small;
            }

            .span-mobile {
                padding-block: 0px !important;
            }

            .tabs-mobile {
                justify-content: space-around !important;
            }

        }

        @media(max-width:767px) {
            .setting-btn-custom {
                width: fit-content !important;
                margin-left: auto;
            }

        }


        @media (max-width: 768px) {
            .cookie-policy h1 {
                font-size: 2rem;
            }

            .cookie-policy h2 {
                font-size: 1.5rem;
            }
        }


        @media(min-width:426px) {
            .post-container-custom {
                min-height: 70vh;
            }


        }
    </style>

@endsection



<script>
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const targetTabId = urlParams.get('tab');

        if (targetTabId) {
            // Find the corresponding nav link and tab pane
            const targetLink = document.querySelector(`.nav-link[href="#${targetTabId}"]`);
            const targetPane = document.getElementById(targetTabId);

            // Deactivate all tabs and panes
            document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));
            document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('show', 'active'));

            // Activate the correct link and pane
            if (targetLink && targetPane) {
                targetLink.classList.add('active');
                targetPane.classList.add('show', 'active');
            }
        } else {
            // If no tab param, activate the first tab by default
            const defaultLink = document.querySelector('.nav-link');
            const defaultPaneId = defaultLink?.getAttribute('href')?.substring(1);
            const defaultPane = document.getElementById(defaultPaneId);

            if (defaultLink && defaultPane) {
                defaultLink.classList.add('active');
                defaultPane.classList.add('show', 'active');
            }
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);

        if (urlParams.has('tab')) {
            const targetTab = urlParams.get('tab');
            const targetLink = document.querySelector(`.nav-link[href="#${targetTab}"]`);
            const allTabs = document.querySelectorAll('.tab-pane');
            const allLinks = document.querySelectorAll('.nav-link');

            allTabs.forEach(tab => tab.classList.remove('show', 'active'));
            allLinks.forEach(link => link.classList.remove('active'));

            if (targetLink) {
                targetLink.classList.add('active');
                const targetContent = document.querySelector(`#${targetTab}`);
                if (targetContent) targetContent.classList.add('show', 'active');
            }
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('#activityTabs button');
        const panes = document.querySelectorAll('#activityTabsContent .tab-pane');

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Deactivate all tabs and panes
                tabs.forEach(t => t.classList.remove('active'));
                panes.forEach(p => p.classList.remove('show', 'active'));

                // Activate the clicked tab and its corresponding pane
                const targetPaneId = this.dataset.bsTarget || this.getAttribute('data-target');
                const targetPane = document.querySelector(targetPaneId);

                this.classList.add('active');
                if (targetPane) {
                    targetPane.classList.add('show', 'active');
                }
            });
        });

        const urlParams = new URLSearchParams(window.location.search);
        let activeTabId = 'commented-tab'; // Default tab

        if (urlParams.has('voted_page')) {
            activeTabId = 'voted-tab';
        } else if (urlParams.has('subscribed_page')) {
            activeTabId = 'subscribed-tab';
        }

        const initialTabButton = document.getElementById(activeTabId);
        if (initialTabButton) {
            initialTabButton.click(); // Simulate a click on the initial tab button
        }
    });
</script>
