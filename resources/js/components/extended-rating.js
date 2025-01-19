import raterJs from 'rater-js';

console.log('extended-rating.js loaded');

document.addEventListener('DOMContentLoaded', function () {
    console.log('Initializing raters...');
    document.querySelectorAll('.rater').forEach(function (rater) {
        const rating = rater.getAttribute('data-rating');
        console.log('Initializing rater:', rater.id, 'with rating:', rating);
        raterJs({
            starSize: 22,
            rating: parseFloat(rating),
            element: rater,
            rateCallback: function rateCallback(rating, done) {
                const input = document.getElementById('rating-' + rater.id.split('-')[1]);
                input.value = rating;
                this.setRating(rating);
                done();
            }
        });
    });
});