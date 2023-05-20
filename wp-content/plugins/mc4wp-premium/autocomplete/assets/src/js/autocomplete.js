document.addEventListener('DOMContentLoaded', function () {
  [].forEach.call(document.querySelectorAll('.mc4wp-form.autocomplete input[type="email"]'), (e)=>{
    mc4wpAutocomplete(e, mc4wp_autocomplete_vars.domains);
  });
}, false);

function mc4wpAutocomplete(inputEl, suggestions) {
  var mc4wpCurrentFocus;
  inputEl.addEventListener("input", function(evt) {
    const atPos = this.value.indexOf('@')
    if(atPos <= 0) {
      return;
    }

    const emailPart = this.value.substring(0, atPos).replaceAll(/<.*>/g, '');
    const domainPrefix = this.value.substring(atPos + 1);
    mc4wpCloseAllLists();
    if (!domainPrefix) { return false;}
    mc4wpCurrentFocus = -1;
    let autocompleteList = document.createElement("div");
    autocompleteList.setAttribute("id", this.id + "mc4wp-autocomplete-list");
    autocompleteList.setAttribute("class", "mc4wp-autocomplete-items");
    autocompleteList.style.width = inputEl.offsetWidth + "px";
    autocompleteList.style.left = inputEl.offsetLeft + "px";
    let autocompleteListHtml = "";
    suggestions.forEach(suggestion => {
      // only add suggestion to list if prefix matches
      if (suggestion.substr(0, domainPrefix.length).toUpperCase() !== domainPrefix.toUpperCase()) {
        return;
      }

      autocompleteListHtml+= "<div>";
      autocompleteListHtml+=  emailPart + "@" + "<strong>" + suggestion.substr(0, domainPrefix.length) + "</strong>";
      autocompleteListHtml+=  suggestion.substr(domainPrefix.length);
      autocompleteListHtml+=  "<input type='hidden' value='" + emailPart + "@" + suggestion + "'>";
      autocompleteListHtml+= "</div>";
    })

    // set HTML for list of suggestions & add to DOM
    autocompleteList.innerHTML = autocompleteListHtml;
    this.parentNode.appendChild(autocompleteList);

    document.querySelectorAll('.mc4wp-form #mc4wp-autocomplete-list div').forEach((item, index) => {
      item.addEventListener("click", function(e) {
        inputEl.value = item.getElementsByTagName("input")[0].value;
        mc4wpCloseAllLists();
      })
    });
  });
  inputEl.addEventListener("keydown", function(evt) {
    var autocompleteList = document.getElementById(this.id + "mc4wp-autocomplete-list");
    if (autocompleteList) autocompleteList = autocompleteList.getElementsByTagName("div");
    switch (evt.code) {
      case 'ArrowDown': {
        mc4wpCurrentFocus++;
        mc4wpAddActive(autocompleteList);
        break;
      }
      case 'ArrowUp': {
        mc4wpCurrentFocus--;
        mc4wpAddActive(autocompleteList);
        break;
      }
      case 'Enter': {
        evt.preventDefault();
        if (mc4wpCurrentFocus > -1) {
          if (autocompleteList) autocompleteList[mc4wpCurrentFocus].click();
        }
        break;
      }
    }
  });

  function mc4wpAddActive(autocompleteList) {
    if (!autocompleteList) return false;
    mc4wpRemoveActive(autocompleteList);
    if (mc4wpCurrentFocus >= autocompleteList.length) mc4wpCurrentFocus = 0;
    if (mc4wpCurrentFocus < 0) mc4wpCurrentFocus = (autocompleteList.length - 1);
    autocompleteList[mc4wpCurrentFocus].classList.add("mc4wp-autocomplete-active");
  }
  function mc4wpRemoveActive(autocompleteList) {
    for (var i = 0; i < autocompleteList.length; i++) {
      autocompleteList[i].classList.remove("mc4wp-autocomplete-active");
    }
  }
  function mc4wpCloseAllLists(elmnt) {
    var autocompleteList = document.getElementsByClassName("mc4wp-autocomplete-items");
    for (var i = 0; i < autocompleteList.length; i++) {
      if (elmnt != autocompleteList[i]) {
        autocompleteList[i].parentNode.removeChild(autocompleteList[i]);
      }
    }
  }
  document.addEventListener("click", evt => mc4wpCloseAllLists(evt.target));
}
