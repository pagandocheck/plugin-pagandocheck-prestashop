{*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<form action="{$action}" id="pgdo-payment">
  <div class="pagando-check">

    <div class="pagando-input">
      <label for="titular">Nombre completo <span>*</span></label>
      <div class="">
        <input id="card_name" placeholder="Ingresa el nombre completo de tu tarjeta" name="card_name" type="text">
      </div>
    </div>

    <div class="pagando-two-block-content">
      <div class="left-block">
        <div class="pagando-input">
          <label for="titular">Número de tarjeta <span>*</span></label>
          <div class="">
            <input id="card_pan" placeholder="Ingresa los 16 dígitos de tu tarjeta" type="text">
            <input id="card_pan_no_spaces" type="hidden" name="card_pan">
          </div>
        </div>
      </div>
      <div class="right-block" style="max-width: 106px;display: flex;align-items: center;padding-top: 10px;">
        <div class="pagando-card-container">
          <img id="visa" class="card-logo" src="https://servicios.pagando.mx/img/logos/Cards/visa.svg" alt="Logo de tarjeta Visa">
          <img id="mastercard" class="card-logo" src="https://servicios.pagando.mx/img/logos/Cards/mastercard.svg" alt="Logo de tarjeta Master Card">
          <img id="carnet" class="card-logo" src="https://servicios.pagando.mx/img/logos/Cards/carnet.svg" alt="Logo de tarjeta Carnet">
        </div>
      </div>
    </div>

    <div class="pagando-two-block-content">
      <div class="left-block">
        <div class="pagando-input">
          <label for="titular">CVC <span>*</span></label>
          <div class="">
            <input id="card_cvv" placeholder="Código Seguridad" name="card_cvv" type="number">
          </div>
        </div>
      </div>

      <div class="right-block">
        <div class="pagando-input">
          <label for="titular">Fecha Expiración<span>*</span></label>
          <div class="">
            <input id="card_exp" placeholder="MM/AA" type="text">
            <input id="card_exp_month" name="card_exp_month" type="hidden">
            <input id="card_exp_year" name="card_exp_year" type="hidden">
          </div>
        </div>
      </div>
    </div>

    <h2 class="pagando-generic-title">Domicilio de tarjeta</h2>

    <div class="pagando-select" style="width:100%;">
      <label>País<span>*</span></label>
      <select id="card_country" name="card_country">
        <option>Selecciona el País</option>
        {foreach from=$countries item=country}
          <option value="{$country->isoCode}">{$country->name}</option>
        {/foreach}
      </select>
    </div>

    <div class="pagando-two-block-content">
      <div class="left-block">
        <div class="pagando-input">
          <label for="titular">Estado <span>*</span></label>
          <div class="">
            <input placeholder="Escribe el estado" type="text" name="card_state">
          </div>
        </div>
      </div>

      <div class="right-block">
        <div class="pagando-input">
          <label for="titular">Ciudad <span>*</span></label>
          <div class="">
            <input placeholder="Escribe la ciudad" type="text" name="card_city">
          </div>
        </div>
      </div>
    </div>

    <div class="pagando-two-block-content">
      <div class="left-block">
        <div class="pagando-input">
          <label for="titular">Municipio <span>*</span></label>
          <div class="">
            <input placeholder="Escribe el municipio" type="text" name="card_district">
          </div>
        </div>
      </div>

      <div class="right-block">
        <div class="pagando-input">
          <label for="titular">Código Postal <span>*</span></label>
          <div class="">
            <input placeholder="5 dígitos" type="number" name="card_zipCode">
          </div>
        </div>
      </div>
    </div>

    <div class="pagando-input">
      <label for="titular">Calle <span>*</span></label>
      <div class="">
        <input placeholder="Nombre de la calle" type="text" name="card_street">
      </div>
    </div>

    <div class="pagando-two-block-content">
      <div class="left-block">
        <div class="pagando-input">
          <label for="titular">Número Exterior <span>*</span></label>
          <div class="">
            <input placeholder="Número de calle" type="text" name="card_noExt">
          </div>
        </div>
      </div>

      <div class="right-block">
        <div class="pagando-input">
          <label for="titular">Número Interior <span>*</span></label>
          <div class="">
            <input placeholder="Opcional" type="text" name="card_noInt">
          </div>
        </div>
      </div>
    </div>

    <h2 class="pagando-generic-title">Promociones</h2>
    <div class="pagando-select" style="width:100%;">
      <label>Promociones</label>
      <select id="card_promotion" name="card_promotion">
        <option>Ingrese el número de tarjeta para ver las promociones.</option>
      </select>
      <input id="card_promotion_promotion_type" name="card_promotion_promotion_type" type="hidden">
      <input id="card_promotion_promotion_time_to_apply" name="card_promotion_promotion_time_to_apply" type="hidden">
      <input id="card_promotion_promotion_months_to_wait" name="card_promotion_promotion_months_to_wait" type="hidden">
    </div>

    <div class="bottom-text">
      <p><span>*</span> Campos requeridos</p>
    </div>

    <input type="hidden" id="aft_token" name="aft_token">
  </div> 
</form>

<script type="text/javascript" src="https://static.pagando.mx/pagando_aft.min.js"></script>

<script>
  const apiURI = '{$apiURI}';
  const token = '{$token}';
  const amount = {$amount};

  {literal}
  // SELECT start
  function updateSelects() {
    var x, i, j, selElmnt, a, b, c;
    x = document.getElementsByClassName("pagando-select");
    for (i = 0; i < x.length; i++) {
      selElmnt = x[i].getElementsByTagName("select")[0];

      const existingDiv = x[i].getElementsByClassName('pagando-select-selected')[0];
      a = existingDiv || document.createElement("DIV");
      if (!existingDiv) {
        x[i].appendChild(a);
        a.addEventListener("click", function(e) {
          e.stopPropagation();
          closeAllSelect(this);
          this.nextSibling.classList.toggle("pagando-select-hide");
          this.classList.add("selected-option");
        });
      }
      a.setAttribute("class", "pagando-select-selected");
      a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;

      b = x[i].getElementsByClassName('pagando-select-items')[0] || document.createElement("DIV");
      b.innerHTML = '';
      b.setAttribute("class", "pagando-select-items pagando-select-hide");

      for (j = 1; j < selElmnt.length; j++) {
        c = document.createElement("DIV");
        c.innerHTML = selElmnt.options[j].innerHTML;
        c.addEventListener("click", function(e) {
          var y, i, k, s, h;
          s = this.parentNode.parentNode.getElementsByTagName("select")[0];
          h = this.parentNode.previousSibling;

          for (i = 0; i < s.length; i++) {
            if (s.options[i].innerHTML == this.innerHTML) {
              s.selectedIndex = i;
              h.innerHTML = this.innerHTML;
              y = this.parentNode.getElementsByClassName("pagando-selected-option");
              for (k = 0; k < y.length; k++) {
                y[k].removeAttribute("class");
              }
              this.setAttribute("class", "pagando-selected-option");
              break;
            }
          }
          h.click();
          promotionSelected(s, this);
        });
        b.appendChild(c);
      }

      x[i].appendChild(b);
    }
  };
  updateSelects();

  function closeAllSelect(elmnt) {
    var x, y, i, arrNo = [];
    x = document.getElementsByClassName("pagando-select-items");
    y = document.getElementsByClassName("pagando-select-selected");
    for (i = 0; i < y.length; i++) {
      if (elmnt == y[i]) {
        arrNo.push(i)
      } 
    }
    for (i = 0; i < x.length; i++) {
      if (arrNo.indexOf(i)) {
        x[i].classList.add("pagando-select-hide");
      }
    }
  }
  document.addEventListener("click", closeAllSelect);
  // SELECT end

  // MASKS start
  const carnetBins = [
    '506432',
    '506430',
    '506410',
    '506369',
    '506357',
    '506353',
    '506332',
    '506313',
    '286900',
    '639484',
    '639559',
    '506202',
    '506201',
    '506203',
    '506212',
    '506215',
    '506214',
    '506217',
    '506281',
    '506283',
    '506280',
    '506297',
    '506299',
    '506262',
    '506263',
    '506265',
    '506269',
    '506273',
    '506272',
    '506274',
    '506277',
    '506276',
    '506279',
    '506278',
    '506245',
    '506247',
    '506251',
    '506250',
    '506253',
    '506255',
    '506254',
    '506257',
    '506259',
    '506258',
    '506222',
    '506221',
    '506228',
    '506237',
    '506199',
    '506320',
    '506329',
    '506319',
    '506336',
    '506339',
    '506301',
    '506300',
    '506303',
    '506302',
    '506306',
    '506312',
    '506311',
    '506318',
    '506309',
    '506393',
    '506340',
    '506343',
    '636379',
    '606333',
    '627535'
  ];
  const carnetBinsRegexpConcat = carnetBins.reduce((acc, bin) => `${acc}|^${bin}`, '').substring(1);
  const carnetBinsRegexp = new RegExp(`(${carnetBinsRegexpConcat})`);

  let ccNumberInput = document.querySelector('#card_pan');
  let ccNumberInputNoSpaces = document.querySelector('#card_pan_no_spaces');
  let ccNumberPattern = /^\d{0,16}$/g;
  let ccNumberSeparator = " ";
  let ccNumberInputOldValue = "";
  let ccNumberInputOldCursor;
		
  let ccExpiryInput = document.querySelector('#card_exp');
  let ccExpiryInputMonth = document.querySelector('#card_exp_month');
  let ccExpiryInputYear = document.querySelector('#card_exp_year');
  let ccExpiryPattern = /^\d{0,4}$/g;
  let ccExpirySeparator = "/";
  let ccExpiryInputOldValue;
  let ccExpiryInputOldCursor;
		
  let ccCVCInput = document.querySelector('#card_cvv');
  let ccCVCPattern = /^\d{0,3}$/g;

  ccNumberInputNoSpaces.value = ccNumberInput.value.replace(/ /g, '');

  let ccCardType = '';
  const ccCardTypePatterns = {
    carnet: carnetBinsRegexp,
    visa: /^4/,
    mastercard: /^5/,
  };
		
  let mask = (value, limit, separator) => {
    var output = [];
    for (let i = 0; i < value.length; i++) {
      if ( i !== 0 && i % limit === 0) {
        output.push(separator);
      }
      
      output.push(value[i]);
    }	
    return output.join("");
  };

  let unmask = (value) => value.replace(/[^\d]/g, '');
  let checkSeparator = (position, interval) => Math.floor(position / (interval + 1));

  let ccNumberInputKeyDownHandler = (e) => {
    let el = e.target;
    ccNumberInputOldValue = el.value;
    ccNumberInputOldCursor = el.selectionEnd;
  };

  let ccNumberInputInputHandler = (e) => {
    let el = e.target,
        newValue = unmask(el.value),
        newCursorPosition;
    
    if ( newValue.match(ccNumberPattern) ) {
      newValue = mask(newValue, 4, ccNumberSeparator);
      
      newCursorPosition = 
        ccNumberInputOldCursor - checkSeparator(ccNumberInputOldCursor, 4) + 
        checkSeparator(ccNumberInputOldCursor + (newValue.length - ccNumberInputOldValue.length), 4) + 
        (unmask(newValue).length - unmask(ccNumberInputOldValue).length);
      
      el.value = (newValue !== "") ? newValue : "";
    } else {
      el.value = ccNumberInputOldValue;
      newCursorPosition = ccNumberInputOldCursor;
    }
    
    el.setSelectionRange(newCursorPosition, newCursorPosition);
    
    highlightCC(el.value);

    const panNoSpaces = el.value.replace(/ /g, '');
    ccNumberInputNoSpaces.value = panNoSpaces;

    if (panNoSpaces.length >= 8 && ccCardType && ccCardType.length > 0) {
      fetchPromotions(panNoSpaces.substring(0, 8), ccCardType.toUpperCase(), amount);
    }
  };

  let highlightCC = (ccValue) => {
    for (const cardType in ccCardTypePatterns) {
      if ( ccCardTypePatterns[cardType].test(ccValue.replace(/ /g, '')) ) {
        ccCardType = cardType;
        break;
      }
    }
    
    if(ccCardType) {
      let activeCC = document.querySelector('.card-logo.active');
      let newActiveCC = document.querySelector(`#${ccCardType}`);
        
      if (activeCC) activeCC.classList.remove('active');
      if (newActiveCC) newActiveCC.classList.add('active');
    } else {
      let activeCC = document.querySelector('.card-logo.active');
      if (activeCC) activeCC.classList.remove('active');
    }
  };

  let ccExpiryInputKeyDownHandler = (e) => {
    let el = e.target;
    ccExpiryInputOldValue = el.value;
    ccExpiryInputOldCursor = el.selectionEnd;
  };

  let ccExpiryInputInputHandler = (e) => {
    let el = e.target,
        newValue = el.value;
    
    newValue = unmask(newValue);
    if ( newValue.match(ccExpiryPattern) ) {
      newValue = mask(newValue, 2, ccExpirySeparator);
      el.value = newValue;
    } else {
      el.value = ccExpiryInputOldValue;
    }

    const [month, year] = el.value.split('/');
    ccExpiryInputMonth.value = month;
    ccExpiryInputYear.value = year;
  };
  
  ccNumberInput.addEventListener('keydown', ccNumberInputKeyDownHandler);
  ccNumberInput.addEventListener('input', ccNumberInputInputHandler);

  ccExpiryInput.addEventListener('keydown', ccExpiryInputKeyDownHandler);
  ccExpiryInput.addEventListener('input', ccExpiryInputInputHandler);
  // MASKS end

  // PROMOTIONS start
  const cardPromotionSelectId = 'card_promotion';
  const cardPromotionTypeId = "card_promotion_promotion_type";
  const cardPromotionTimeToApplyId = "card_promotion_promotion_time_to_apply";
  const cardPromotionMonthsToWaitId = "card_promotion_promotion_months_to_wait";

  let promotions = [{name: 'Ingrese el el número de tarjeta para ver las promociones'}];

  function fetchPromotions(bin, cardBrand, amount) {
    const request = new XMLHttpRequest();
    request.onreadystatechange = () => {
      if(request.readyState === 4) {
        if(request.status === 200) { 
          const response = JSON.parse(request.response);
          
          if (response.data.length === 0) {
            promotions = [{name: 'No hay promociones disponibles'}];
          } else {
            promotions = [{name: 'Seleccione una promoción'}].concat(response.data);
          }
          updatePromotions();
        } else {
          console.error(request);
        } 
      }
    }

    const payload = {
        bin,
        cardBrand,
        amount
    };
    
    request.open('POST', `${apiURI}pagando/promotions/get-terminal-promotions-nouser`, true);
    request.setRequestHeader('Content-Type', 'application/json');
    request.setRequestHeader('Authorization', token);
    request.send(JSON.stringify(payload));
  }

  function updatePromotions() {
    const promotionsSelect = document.getElementById(cardPromotionSelectId);
    const options = [];
    promotionsSelect.innerHTML = '';
    promotions.forEach(({name}, i) => {
      const newOption = document.createElement("option");
      newOption.text = name;
      newOption.value = i;
      promotionsSelect.appendChild(newOption);
    });
    updateSelects();
  }

  function promotionSelected (select, option) {
    if (select.id === cardPromotionSelectId) {
      const selectedPromotion = promotions.find((p) => p.name === option.innerHTML);
      const typeInput = document.getElementById(cardPromotionTypeId);
      const timeToApplyInput = document.getElementById(cardPromotionTimeToApplyId);
      const monthsToWaitInput = document.getElementById(cardPromotionMonthsToWaitId);

      if(selectedPromotion && selectedPromotion.time && selectedPromotion.minAmount && selectedPromotion.promotionType) {
        typeInput.value = selectedPromotion.promotionType;
        timeToApplyInput.value = selectedPromotion.time;
        monthsToWaitInput.value = selectedPromotion.monthsToWait || 0;
      } else {
        typeInput.value = null;
        timeToApplyInput.value = null;
        monthsToWaitInput.value = null;
      }
    }
  };

  updatePromotions();

  // AFT start
  function loadScript( url, callback ) {
    var script = document.createElement( "script" )
    script.type = "text/javascript";
    if(script.readyState) {  // only required for IE <9
      script.onreadystatechange = function() {
        if ( script.readyState === "loaded" || script.readyState === "complete" ) {
          script.onreadystatechange = null;
          callback();
        }
      };
    } else {  //Others
      script.onload = function() {
        callback();
      };
    }

    script.src = url;
    document.getElementsByTagName( "head" )[0].appendChild( script );
  }

  const aftTokenInputId = 'aft_token';
  const aftTokenInput = document.getElementById(aftTokenInputId);
  loadScript('https://static.pagando.mx/pagando_aft.min.js', function() {
    aftTokenInput.value = PagandoCheck.getAFT(); 
  });
  // AFT end

  {/literal}
</script>

{literal}
<style>
  .pagando-check {
    max-width: 440px;
  }
  .pagando-check .pagando-input {
    width: 100%;
    margin-bottom: 10px;
  }
  .pagando-check .pagando-input label {
    font-family: "Avenir Next", arial, sans-serif;
    font-size: 14px;
    font-weight: normal;
    line-height: normal;
    color: #547286;
    margin-bottom: 5px;
    display: block;
    text-transform: none;
  }
  .pagando-check .pagando-input label span {
    font-weight: normal;
    font-size: 14px;
    color: #C23E37;
  }
  .pagando-check .pagando-input input {
    box-sizing: border-box;
    width: 100%;
    border-radius: 3px;
    border: solid 1px #dde3e8;
    padding: 10px 14px;
    font-size: 14px;
    font-weight: 600;
    line-height: normal;
    color: #294F68;
    outline: none;
    font-family: "Avenir Next", arial, sans-serif;
    margin-top: 0px;
    transition: 0.3s;
  }
  .pagando-check .pagando-input input[type=number]::-webkit-inner-spin-button,
  .pagando-check .pagando-input input[type=number]::-webkit-outer-spin-button {
    display: none;
  }
  .pagando-check .pagando-input input:focus {
    border-color: #0077e2;
  }
  .pagando-check .pagando-input ::placeholder {
    color: #A9B9C3;
    opacity: 1;
    font-weight: normal;
  }
  .pagando-check .pagando-input :-ms-input-placeholder {
    color: #A9B9C3;
    font-weight: normal;
  }
  .pagando-check .pagando-input ::-ms-input-placeholder {
    color: #A9B9C3;
    font-weight: normal;
  }
  .pagando-check .pagando-input input::-webkit-outer-spin-button,
  .pagando-check .pagando-input input::-webkit-inner-spin-button {
    -webkit-appearance: none !important;
    margin: 0;
  }
  .pagando-check .pagando-input input[type=number] {
    -moz-appearance: textfield !important;
  }
  .pagando-check .pagando-input.select-inside input {
    padding-left: 110px;
  }
  .pagando-check .pagando-input .date-input {
    text-transform: uppercase;
  }
  .pagando-check .pagando-input ::-webkit-inner-spin-button,
  .pagando-check .pagando-input ::-webkit-calendar-picker-indicator {
    display: none;
    -webkit-appearance: none;
  }
  .pagando-check .pagando-card-container {
    display: flex;
    justify-content: space-between;
  }
  .pagando-check .pagando-card-container img {
    height: 23px;
    margin: 0px 1px;
    opacity: 0.5;
    border-radius: 5px;
    object-fit: cover;
  }
  .pagando-check .pagando-card-container img.active {
    opacity: 1;
  }
  .pagando-check .pagando-two-block-content {
    display: flex;
    justify-content: space-between;
  }
  .pagando-check .pagando-two-block-content .left-block {
    padding-right: 5px;
    width: 100%;
  }
  .pagando-check .pagando-two-block-content .right-block {
    padding-left: 5px;
    width: 100%;
  }
  .pagando-check .pagando-generic-title {
    font-weight: 600;
    font-size: 18px;
    color: #294F68;
    margin: 0px;
    margin-bottom: 10px;
    margin-top: 20px;
    line-height: 1;
    font-family: "Avenir Next", arial, sans-serif;
  }
  .pagando-check .pagando-select {
    position: relative;
    font-family: arial, sans-serif;
    margin-bottom: 10px;
    cursor: pointer;
  }
  .pagando-check .pagando-select label {
    font-family: "Avenir Next", arial, sans-serif;
    font-size: 14px;
    font-weight: normal;
    line-height: normal;
    color: #547286;
    margin-bottom: 5px;
    display: block;
    text-transform: none;
  }
  .pagando-check .pagando-select label span {
    font-weight: normal;
    font-size: 14px;
    color: #C23E37;
  }
  .pagando-check .pagando-select ::-webkit-scrollbar {
    width: 5px;
    border-radius: 20px;
  }
  .pagando-check .pagando-select ::-webkit-scrollbar-track {
    box-shadow: none;
    background-color: #d4d9dd;
  }
  .pagando-check .pagando-select ::-webkit-scrollbar-thumb {
    background-color: #788995;
    border-radius: 20px;
  }
  .pagando-check .pagando-select.inside-input {
    position: absolute;
    margin: 0;
    top: 0px;
  }
  .pagando-check .pagando-select.inside-input .pagando-select-selected {
    padding: 16px 15px;
  }
  .pagando-check .pagando-select select {
    display: none;
  }
  .pagando-check .pagando-select .placeholder-select {
    color: #788995;
  }
  .pagando-check .pagando-select-selected {
    background-color: #fff;
    border: solid 1px #dde3e8;
    padding: 10px 14px;
    position: relative;
    transition: 0.3s;
    border-radius: 3px;
    font-family: "Avenir Next", arial, sans-serif;
    font-size: 14px;
    color: #294F68;
  }
  .pagando-check .selected-option {
    font-weight: 600;
  }
  .pagando-check .pagando-select-selected:after {
    position: absolute;
    content: "";
    top: 7px;
    right: 12px;
    width: 20px;
    height: 20px;
    clip-path: polygon(100% 40%, 40% 100%, 100% 100%);
    background: #A9B9C3;
  }
  .pagando-check .pagando-select-selected.pagando-select-arrow-active {
    border: solid 1px #0077e2;
  }
  .pagando-check .pagando-select-items {
    max-height: 253px;
    overflow: auto;
    position: absolute;
    background: #fff;
    cursor: pointer;
    font-family: "Avenir Next", arial, sans-serif;
    font-size: 16px;
    color: #1d252a;
    top: 100%;
    left: 0;
    right: 0;
    z-index: 99;
    border-radius: 0 0 3px 3px;
    box-shadow: 0 2px 6px 0 rgba(36, 36, 36, 0.3);
  }
  .pagando-check .pagando-select-items div {
    padding: 10px 14px;
    cursor: pointer;
    border-bottom: solid 1px #dde3e8;
    transition: 0.3s;
    font-family: "Avenir Next", arial, sans-serif;
    font-size: 14px;
    font-weight: normal;
    line-height: normal;
    color: #547286;
  }
  .pagando-check .pagando-select-items div:last-child {
    border-bottom: none;
  }
  .pagando-check .pagando-select-hide {
    display: none;
  }
  .pagando-check .pagando-select-items div:hover, .pagando-check .pagando-selected-option {
    background-color: rgba(221, 227, 232, 0.5);
  }
  .pagando-check .bottom-text p {
    font-family: "Avenir Next", arial, sans-serif;
    font-size: 14px;
    font-weight: normal;
    line-height: normal;
    color: #547286;
    margin-bottom: 20px;
  }
  .pagando-check .bottom-text p span {
    font-weight: normal;
    color: #C23E37;
  }

  @media (max-width: 576px) {
    .pagando-check .pagando-two-block-content {
      display: initial !important;
    }
    .pagando-check .pagando-two-block-content .left-block {
      padding-right: 0px;
    }
    .pagando-check .pagando-two-block-content .right-block {
      padding-left: 0px;
    }
    .pagando-check .pagando-card-container {
      margin-bottom: 10px !important;
      margin-top: -10px !important;
    }
  }
</style>
{/literal}