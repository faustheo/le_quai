$(document).ready(function () {
    const $bookingHoursSelect = $('.js-booking-hours');
    // Vérifie si le formulaire de réservation est présent sur la page
    if ($('#booking-form').length > 0) {
    }


    $('.js-booking-date').on('change', function () {
        // Récupérer la date sélectionnée
        const selectedDate = $(this).val();

        // Faire une requête AJAX pour récupérer les heures d'ouverture et de fermeture pour la date sélectionnée
        $.getJSON('/get-opening-hours/' + selectedDate, function (data) {
            // Utilisez les heures d'ouverture et de fermeture pour générer les tranches de 15 minutes
            const generateTimeSlots = function (openingTime, closingTime) {
                const timeSlots = [];
                let currentTime = new Date(openingTime);
                const closingTimeMinusOneHour = new Date(closingTime.getTime() - 60 * 60 * 1000); // -1 heure

                while (currentTime <= closingTimeMinusOneHour) {
                    const localTime = currentTime.toLocaleString('en-US', { hour: '2-digit', minute: '2-digit', hour12: false });
                    timeSlots.push(localTime);
                    currentTime.setMinutes(currentTime.getMinutes() + 15);
                }

                return timeSlots;
            }



            const selectedDateObj = new Date(selectedDate);

            const lunchOpening = new Date(selectedDateObj.getFullYear(), selectedDateObj.getMonth(), selectedDateObj.getDate(), data.lunchOpening.split(':')[0], data.lunchOpening.split(':')[1]);
            const lunchClosing = new Date(selectedDateObj.getFullYear(), selectedDateObj.getMonth(), selectedDateObj.getDate(), data.lunchClosing.split(':')[0], data.lunchClosing.split(':')[1]);
            const dinnerOpening = new Date(selectedDateObj.getFullYear(), selectedDateObj.getMonth(), selectedDateObj.getDate(), data.dinnerOpening.split(':')[0], data.dinnerOpening.split(':')[1]);
            const dinnerClosing = new Date(selectedDateObj.getFullYear(), selectedDateObj.getMonth(), selectedDateObj.getDate(), data.dinnerClosing.split(':')[0], data.dinnerClosing.split(':')[1]);

            const lunchHours = generateTimeSlots(lunchOpening, lunchClosing);
            const dinnerHours = generateTimeSlots(dinnerOpening, dinnerClosing);

            // Vide les options existantes et ajoute les nouvelles options de tranches horaires
            $bookingHoursSelect.empty();
            lunchHours.concat(dinnerHours).forEach(hour => {
                $bookingHoursSelect.append($('<option>', {
                    value: hour,
                    text: hour
                }));
            });
        });
    });

    // Déclenche manuellement l'événement 'change' pour remplir les options initiales
    $('.js-booking-date').trigger('change');

    $('#booking-form').on('submit', function (event) {
        event.preventDefault();

        // Récupére la date sous forme de chaîne de caractères au format 'Y-m-d'
        const dateString = new Date($('.js-booking-date').val()).toISOString().slice(0, 10);

        // Remplace la valeur du champ date par la chaîne de caractères formatée
        $('.js-booking-date').val(dateString);

        // Récupére l'heure locale sélectionnée
        const selectedLocalTime = $bookingHoursSelect.val();

        // Remplace la valeur du champ d'heure par l'heure locale sélectionnée
        $bookingHoursSelect.val(selectedLocalTime).attr('name', 'booking[hours]');

        // Soumettre le formulaire
        this.submit();
    });
});


$(document).ready(function () {
    function submitBookingForm() {
        // Récupére le formulaire et les données du formulaire
        const form = document.getElementById('booking-form');
        const formData = new FormData(form);

        // Utilise Fetch API pour soumettre le formulaire en AJAX
        fetch('/submit-booking', {
            method: 'POST',
            body: formData
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Invalid form data');
                }
                return response.json();
            })
            .then(data => {
                // Met à jour le nombre de places disponibles
                document.getElementById('available-seats').innerText = data.available_seats;
                Swal.fire(
                    'Réservation réussie!',
                    data.message,
                    'success'
                );
            })
            .catch(error => {
                console.error('Error submitting booking form:', error);
            });
    }

    function refreshAvailableSeats() {
        fetch('/max/guests', { cache: 'no-store' }) // Ajout de l'option 'cache: no-store'
            .then(response => response.json())
            .then(data => {
                // Mettre à jour les places disponibles dans le formulaire
                document.querySelector('#booking_guests').setAttribute('max', data.available_seats);

                // Mettre à jour l'affichage des places disponibles sur la page
                document.querySelector('#available-seats').textContent = data.available_seats;
            });
    }

    function pollAvailableSeats() {

        refreshAvailableSeats();


        setInterval(refreshAvailableSeats, 5000);
    }

    document.getElementById('booking-form').addEventListener('submit', (event) => {
        event.preventDefault(); // Empêcher l'envoi du formulaire par défaut

        // Soumettre le formulaire via AJAX
        submitBookingForm();

        // Mettre à jour le nombre de places disponibles après la soumission du formulaire
        refreshAvailableSeats();
    });

    // Démarrez la vérification périodique des places disponibles
    pollAvailableSeats();
});
