/* global coreui */

/**
 * --------------------------------------------------------------------------
 * CoreUI Boostrap Admin Template (v4.2.0): colors.js
 * License (https://coreui.io/pro/license)
 * --------------------------------------------------------------------------
 */
document.querySelectorAll('.theme-color').forEach(element => {
  const color = getComputedStyle(element, null).getPropertyValue('background-color');
  const table = document.createElement('table');
  table.classList.add('w-100');
  table.innerHTML = `
      <table class="w-100">
        <tr>
          <td class="text-muted">HEX:</td>
          <td class="font-weight-bold">${coreui.Utils.rgbToHex(color)}</td>
        </tr>
        <tr>
          <td class="text-muted">RGB:</td>
          <td class="font-weight-bold">${color}</td>
        </tr>
      </table>
    `;
  element.parentNode.append(table);
});
//# sourceMappingURL=colors.js.map