document.addEventListener('DOMContentLoaded', function () {
  var tabs = document.querySelectorAll('.fp-tab');
  var cards = document.querySelectorAll('.fp-card[data-type]');
  if (!tabs.length) return;

  tabs.forEach(function (tab) {
    tab.addEventListener('click', function () {
      var filter = tab.dataset.filter;
      tabs.forEach(function (t) { t.classList.toggle('is-active', t === tab); });
      cards.forEach(function (card) {
        card.classList.toggle('is-hidden', filter !== 'all' && card.dataset.type !== filter);
      });
    });
  });
});