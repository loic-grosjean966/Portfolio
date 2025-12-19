 $(document).ready(function() {
            // Smooth scrolling pour les liens de navigation
            $('a[href^="#"]').on('click', function(e) {
                e.preventDefault();
                const target = $(this.getAttribute('href'));
                if (target.length) {
                    $('html, body').stop().animate({
                        scrollTop: target.offset().top - 70
                    }, 1000);
                }
            });

            // Animation de la navbar au scroll
            $(window).scroll(function() {
                if ($(this).scrollTop() > 50) {
                    $('.navbar').addClass('scrolled');
                    $('#scrollTop').addClass('show');
                } else {
                    $('.navbar').removeClass('scrolled');
                    $('#scrollTop').removeClass('show');
                }

                // Animation des éléments au scroll
                $('.fade-in-up').each(function() {
                    const elementTop = $(this).offset().top;
                    const viewportBottom = $(window).scrollTop() + $(window).height();
                    
                    if (elementTop < viewportBottom - 100) {
                        $(this).css('animation-play-state', 'running');
                    }
                });
            });

            // Bouton scroll to top
            $('#scrollTop').click(function() {
                $('html, body').animate({scrollTop: 0}, 800);
            });

            // Animation des barres de compétences
            function animateSkills() {
                $('.progress-bar').each(function() {
                    const bar = $(this);
                    const width = bar.data('width');
                    bar.animate({width: width + '%'}, 1500, function() {
                        bar.text(width + '%');
                    });
                });
            }

            // Déclenchement de l'animation des compétences au scroll
            let skillsAnimated = false;
            $(window).scroll(function() {
                const skillsSection = $('#competences');
                if (skillsSection.length) {
                    const skillsTop = skillsSection.offset().top;
                    const viewportBottom = $(window).scrollTop() + $(window).height();
                    
                    if (viewportBottom > skillsTop && !skillsAnimated) {
                        animateSkills();
                        skillsAnimated = true;
                    }
                }
            });

            // Gestion du formulaire de contact
            $('.contact-form').submit(function(e) {
                e.preventDefault();
                alert('Merci pour votre message ! Je vous répondrai bientôt.');
                this.reset();
            });

            // Animation au chargement de la page
            setTimeout(function() {
                $('.fade-in-up').css('animation-play-state', 'running');
            }, 100);
        });