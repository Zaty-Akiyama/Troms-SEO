const urlWrapperes = document.querySelectorAll('.jsUrlWrapper');

const tmpInputEl = document.createElement('input');
tmpInputEl.setAttribute('type', 'url');
tmpInputEl.setAttribute('pattern', 'https://.*');
tmpInputEl.setAttribute('placeholder', 'https://example.com');
tmpInputEl.setAttribute('size', '30');

const insertInput = function () {
  const that = this.input;
  const i = this.i+1;
  const j = this.j;
  const last_input = that.parentNode.querySelector('input:last-of-type');
  if ( last_input !== that && that.value === '' ) {
    that.remove();
  }else if ( last_input === that && that.value !== '' ) {
    const newInput = tmpInputEl.cloneNode();
    newInput.addEventListener( 'focusout', {handleEvent: insertInput, i: i, j: j, input: newInput});
    newInput.setAttribute('name', 'url_'+j+'_'+ i);
    newInput.setAttribute('id', 'url_'+j+'_'+i);

    that.parentNode.insertBefore( newInput, that.nextSibling);
  }
}

urlWrapperes.forEach( (wrapper, j) => {
  const inputs = wrapper.querySelectorAll('input');

  inputs.forEach( (input, i) => {
    input.addEventListener('focusout', {handleEvent: insertInput, i: i+1, input: input, j: j});
  })
});
