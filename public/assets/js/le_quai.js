$(document).ready(function () {
    const $bookingHoursSelect = $('.js-booking-hours'); // Sélectionnez le champ de sélection des heures

    $('.js-booking-date').on('change', function () {
        // Récupérer la date sélectionnée
        const selectedDate = $(this).val();
        console.log(selectedDate);

        // Faire une requête AJAX pour récupérer les heures d'ouverture et de fermeture pour la date sélectionnée
        $.getJSON('/get-opening-hours/' + selectedDate, function (data) {
            // Utilisez les heures d'ouverture et de fermeture pour générer les tranches de 15 minutes
            const generateTimeSlots = function (openingTime, closingTime) {
                const timeSlots = [];
                let currentTime = new Date(openingTime);

                while (currentTime < closingTime) {
                    timeSlots.push(currentTime.toISOString().substr(11, 5));
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

    $('form').on('submit', function (event) {
        event.preventDefault();

        // Récupérer la date sous forme de chaîne de caractères au format 'Y-m-d'
        const dateString = new Date($('.js-booking-date').val()).toISOString().slice(0, 10);

        // Remplacer la valeur du champ date par la chaîne de caractères formatée
        $('.js-booking-date').val(dateString);

        // Soumettre le formulaire
        this.submit();
    });


});
