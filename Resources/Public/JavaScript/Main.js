var TYPO3 = TYPO3 || {};
TYPO3.tea = {};

TYPO3.tea.makeSortable = function (table) {
  var th = table.tHead,
    i;
  th && (th = th.rows[0]) && (th = th.cells);
  if (th) i = th.length;
  else return;
  while (--i >= 0)
    (function (i) {
      var dir = 1;
      th[i].addEventListener("click", function () {
        TYPO3.tea.sortTable(table, i, (dir = 1 - dir));
      });
    })(i);
};

TYPO3.tea.sortTable = function (table, col, reverse) {
  var tb = table.tBodies[0],
    tr = Array.prototype.slice.call(tb.rows, 0),
    i;
  reverse = -(+reverse || -1);
  tr = tr.sort(function (a, b) {
    return (
      reverse *
      a.cells[col].textContent
        .trim()
        .localeCompare(b.cells[col].textContent.trim())
    );
  });
  for (i = 0; i < tr.length; ++i) tb.appendChild(tr[i]);
};

document.addEventListener("DOMContentLoaded", function () {
  var t = document.querySelectorAll(".tx-tea table"),
    i = t.length;
  while (--i >= 0) {
    TYPO3.tea.makeSortable(t[i]);
  }
});
