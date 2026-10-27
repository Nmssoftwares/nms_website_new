// Scroll reveal
const io = new IntersectionObserver((entries)=>{
  entries.forEach(e=>{ if(e.isIntersecting){ e.target.classList.add('visible'); io.unobserve(e.target);} });
},{threshold:.15});
document.querySelectorAll('.reveal').forEach(el=>io.observe(el));

// Mobile nav toggle
const burger = document.querySelector('.burger');
if(burger){
  burger.addEventListener('click', ()=>{
    document.getElementById('navlinks').classList.toggle('open');
  });
}

// Contact form: send without reloading the page
(function(){
  var form = document.getElementById('contact-form');
  if(!form || !window.fetch || !window.FormData) return;   // old browsers: normal submit still works

  var box = document.getElementById('cf-status');
  var btn = form.querySelector('button[type="submit"]');

  function showStatus(text, isError){
    if(!box) return;
    box.textContent = text;
    box.classList.toggle('is-error', !!isError);
    box.hidden = false;
    box.scrollIntoView({behavior:'smooth', block:'nearest'});
  }
  function clearMessages(){
    if(box){ box.hidden = true; box.textContent = ''; }
    form.querySelectorAll('.err').forEach(function(e){ e.remove(); });
    document.querySelectorAll('.contact-ok:not(#cf-status)').forEach(function(e){ e.remove(); }); // old server-side message
  }

  form.addEventListener('submit', function(ev){
    ev.preventDefault();
    clearMessages();

    var label = btn.textContent;
    btn.disabled = true;
    btn.textContent = 'Sending…';

    fetch(form.action, {
      method: 'POST',
      body: new FormData(form),
      headers: {'Accept':'application/json', 'X-Requested-With':'XMLHttpRequest'},
      credentials: 'same-origin'
    })
    .then(function(res){
      return res.json().catch(function(){ return {}; }).then(function(data){
        return {status: res.status, ok: res.ok, data: data};
      });
    })
    .then(function(r){
      if(r.ok){
        form.reset();
        showStatus(r.data.message || 'Thanks! Your message has been sent.', false);
      } else if(r.status === 422 && r.data.errors){
        Object.keys(r.data.errors).forEach(function(name){
          var field = form.elements[name];
          if(!field) return;
          var d = document.createElement('div');
          d.className = 'err';
          d.textContent = r.data.errors[name][0];
          field.insertAdjacentElement('afterend', d);
        });
        showStatus('Please check the highlighted fields and try again.', true);
      } else if(r.status === 419){
        showStatus('Your session expired. Please reload the page and send again.', true);
      } else if(r.status === 429){
        showStatus('Too many attempts. Please wait a minute and try again.', true);
      } else {
        showStatus('Something went wrong. Please try again in a moment.', true);
      }
    })
    .catch(function(){
      showStatus('Could not reach the server. Check your connection and try again.', true);
    })
    .then(function(){
      btn.disabled = false;
      btn.textContent = label;
    });
  });
})();