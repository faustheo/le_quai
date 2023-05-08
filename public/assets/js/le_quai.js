$(document).ready(function () {
    const $bookingHoursSelect = $('.js-booking-hours');
    // Vérifiez si le formulaire de réservation est présent sur la page
    if ($('#booking-form').length > 0) {
        // Le reste de votre code JavaScript
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

                while (currentTime < closingTime) {
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

            // Vide les options existantes et ajoutez les nouvelles options de tranches horaires
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

        // Récupérer la date sous forme de chaîne de caractères au format 'Y-m-d'
        const dateString = new Date($('.js-booking-date').val()).toISOString().slice(0, 10);

        // Remplacer la valeur du champ date par la chaîne de caractères formatée
        $('.js-booking-date').val(dateString);

        // Récupérer l'heure locale sélectionnée
        const selectedLocalTime = $bookingHoursSelect.val();

        // Remplacer la valeur du champ d'heure par l'heure locale sélectionnée
        $bookingHoursSelect.val(selectedLocalTime).attr('name', 'booking[hours]');

        // Affichez les valeurs avant d'empêcher la soumission par défaut
        console.log('Selected Date:', $('.js-booking-date').val());
        console.log('Selected Hour:', $bookingHoursSelect.val());

        // Soumettre le formulaire
        this.submit();
    });
});
