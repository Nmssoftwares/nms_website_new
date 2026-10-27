@extends('admin.layout')

@section('title', 'Settings')

@section('content')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-settings.css') }}?v={{ @filemtime(public_path('css/admin-settings.css')) }}">
@endpush


<div class="page-head">
  <h1>Site Settings</h1>
</div>

@php
  $v = fn($k) => old($k, $setting->opt($k));
@endphp

<form class="st-form" method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
  @csrf
  @method('PUT')

  {{-- ===== Logo ===== --}}
  <section class="st-card">
    <h2>Site Logo</h2>
    <p class="hint">Shown in the website header and footer, the admin sidebar and the login card. A PNG with a transparent background works best.</p>

    <div class="logo-prev" id="logoPrev">
      @if($setting->logoUrl())
        <img src="{{ $setting->logoUrl() }}" alt="Current logo" id="logoImg">
      @else
        <em id="logoEmpty">No logo yet — the text logo is showing.</em>
      @endif
    </div>

    <label class="st-file">
      <input type="file" name="logo" id="logoInput" accept="image/*">
      <span class="ico"><svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg></span>
      <span><b>Choose logo file</b><span class="sub">PNG, JPG or WebP · up to 2 MB</span></span>
    </label>
    <div class="fname" id="logoName"></div>
    @error('logo')<div class="err">{{ $message }}</div>@enderror
  </section>

  {{-- ===== Background ===== --}}
  <section class="st-card">
    <h2>Background Image</h2>
    <p class="hint">Used behind the home page hero and on the admin login page. Adjust the controls and watch the preview — nothing changes on the site until you click Save.</p>

    <div class="prev-head">
      <span class="st-label st-label--flush">Live preview</span>
      <div class="seg" id="modeSeg">
        <label><input type="radio" name="_mode" value="hero" checked><span>Home hero</span></label>
        <label><input type="radio" name="_mode" value="login"><span>Login page</span></label>
      </div>
    </div>

    <div class="bg-prev" id="bgPrev">
      @if($setting->bgImageUrl())
        <div class="layer" id="bgLayer" style="background-image:url('{{ $setting->bgImageUrl() }}');"></div>
      @else
        <div class="layer" id="bgLayer" style="display:none;"></div>
        <div class="none" id="bgNone">No background yet — the default look is used.</div>
      @endif
      <div class="wash" id="bgWash"></div>
      <div class="fake"><i></i><i></i><i></i></div>
    </div>

    <div class="st-block">
      <label class="st-file">
        <input type="file" name="bg_image" id="bgInput" accept="image/*">
        <span class="ico"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg></span>
        <span><b>Choose background photo</b><span class="sub">Wide photo, about 1920 px · up to 6 MB</span></span>
      </label>
      <div class="fname" id="bgName"></div>
      @error('bg_image')<div class="err">{{ $message }}</div>@enderror
      @if($setting->bgImageUrl())
        <label class="rm"><input type="checkbox" name="remove_bg" value="1"> Remove current background image</label>
      @endif
    </div>

    <div class="opts st-block">
      <div>
        <span class="st-label">Size fitting</span>
        <div class="seg">
          <label><input type="radio" name="bg_fit" value="cover"   @checked($v('bg_fit')==='cover')><span>Fill</span></label>
          <label><input type="radio" name="bg_fit" value="contain" @checked($v('bg_fit')==='contain')><span>Fit inside</span></label>
          <label><input type="radio" name="bg_fit" value="stretch" @checked($v('bg_fit')==='stretch')><span>Stretch</span></label>
        </div>
        @error('bg_fit')<div class="err">{{ $message }}</div>@enderror
      </div>
      <div>
        <span class="st-label">Vertical position</span>
        <div class="seg">
          <label><input type="radio" name="bg_position" value="top"    @checked($v('bg_position')==='top')><span>Top</span></label>
          <label><input type="radio" name="bg_position" value="center" @checked($v('bg_position')==='center')><span>Center</span></label>
          <label><input type="radio" name="bg_position" value="bottom" @checked($v('bg_position')==='bottom')><span>Bottom</span></label>
        </div>
        @error('bg_position')<div class="err">{{ $message }}</div>@enderror
      </div>
    </div>

    <div class="st-block">
      <div class="prev-head prev-head--14">
        <span class="st-label st-label--flush">Image look</span>
        <button type="button" class="linkbtn" id="resetLook">Reset to defaults</button>
      </div>
      <div class="sliders">
        <div class="srow">
          <div class="nm">Overlay<small>Lower = photo shows more</small></div>
          <input type="range" name="bg_veil" id="rVeil" min="0" max="100" step="1" value="{{ $v('bg_veil') }}" data-default="55">
          <output id="oVeil"></output>
        </div>
        <div class="srow">
          <div class="nm">Brightness<small>Wake up a dull photo</small></div>
          <input type="range" name="bg_brightness" id="rBri" min="60" max="140" step="1" value="{{ $v('bg_brightness') }}" data-default="105">
          <output id="oBri"></output>
        </div>
        <div class="srow">
          <div class="nm">Color<small>Saturation</small></div>
          <input type="range" name="bg_saturation" id="rSat" min="50" max="150" step="1" value="{{ $v('bg_saturation') }}" data-default="110">
          <output id="oSat"></output>
        </div>
        <div class="srow">
          <div class="nm">Blur<small>Sharp → soft</small></div>
          <input type="range" name="bg_blur" id="rBlur" min="0" max="12" step="1" value="{{ $v('bg_blur') }}" data-default="3">
          <output id="oBlur"></output>
        </div>
      </div>
      @foreach(['bg_veil','bg_brightness','bg_saturation','bg_blur'] as $f)
        @error($f)<div class="err">{{ $message }}</div>@enderror
      @endforeach
    </div>
  </section>

  {{-- ===== Theme colours & fonts ===== --}}
  @php
    $thA = old('theme_a', $setting->themeColor('a'));
    $thB = old('theme_b', $setting->themeColor('b'));
    $fBody = old('font_body', $setting->fontName('body'));
    $fHead = old('font_heading', $setting->fontName('heading'));
    $presets = [
      ['Ocean Blue',   '#2563EB', '#0EA5E9'],
      ['Royal Purple', '#7C3AED', '#06B6D4'],
      ['Emerald',      '#059669', '#14B8A6'],
      ['Sunset',       '#EA580C', '#F59E0B'],
      ['Rose',         '#E11D48', '#FB7185'],
      ['Midnight',     '#1E3A8A', '#6366F1'],
      ['Graphite',     '#334155', '#64748B'],
    ];
  @endphp
  <section class="st-card">
    <h2>Theme colours &amp; fonts</h2>
    <p class="hint">Changes the colours and fonts of the whole website, this admin panel and the login page. Pick a ready-made palette or choose your own, check the preview, then click Save.</p>

    <span class="st-label">Ready-made palettes</span>
    <div class="th-presets">
      @foreach($presets as $preset)
        <button type="button" class="th-preset" data-a="{{ $preset[1] }}" data-b="{{ $preset[2] }}" title="{{ $preset[0] }}">
          <i style="background:linear-gradient(120deg,{{ $preset[1] }},{{ $preset[2] }})"></i>{{ $preset[0] }}
        </button>
      @endforeach
    </div>

    <div class="opts st-block">
      <div>
        <span class="st-label">Main colour</span>
        <div class="th-pick"><input type="color" id="thA" name="theme_a" value="{{ $thA }}"><code id="thAHex">{{ $thA }}</code></div>
        @error('theme_a')<div class="err">{{ $message }}</div>@enderror
      </div>
      <div>
        <span class="st-label">Second colour</span>
        <div class="th-pick"><input type="color" id="thB" name="theme_b" value="{{ $thB }}"><code id="thBHex">{{ $thB }}</code></div>
        @error('theme_b')<div class="err">{{ $message }}</div>@enderror
      </div>
    </div>

    <div class="opts st-block">
      <div>
        <label class="st-label" for="fHead">Heading font</label>
        <select name="font_heading" id="fHead" class="th-select">
          @foreach(\App\Models\Setting::FONTS as $f)
            <option value="{{ $f }}" @selected($fHead === $f)>{{ $f }}</option>
          @endforeach
        </select>
        @error('font_heading')<div class="err">{{ $message }}</div>@enderror
      </div>
      <div>
        <label class="st-label" for="fBody">Text font</label>
        <select name="font_body" id="fBody" class="th-select">
          @foreach(\App\Models\Setting::FONTS as $f)
            <option value="{{ $f }}" @selected($fBody === $f)>{{ $f }}</option>
          @endforeach
        </select>
        @error('font_body')<div class="err">{{ $message }}</div>@enderror
      </div>
    </div>

    <div class="st-block">
      <div class="prev-head prev-head--12">
        <span class="st-label st-label--flush">Live preview</span>
        <button type="button" class="linkbtn" id="thReset">Reset to default look</button>
      </div>
      <div class="th-prev" id="thPrev">
        <span class="th-tag">Preview</span>
        <h3 id="thHead">Software that moves your business forward</h3>
        <p id="thText">This is how normal text will look on your website. Buttons and highlights use your two colours.</p>
        <span class="th-btn">Contact us</span>
      </div>
    </div>
  </section>

  {{-- ===== Header & hero text ===== --}}
  @php
    $hdr = old('header_style', $setting->headerStyle());
    $hTxt = old('hero_text', $setting->heroText());
    $hCol = old('hero_text_color', $setting->heroTextColor());
  @endphp

  <section class="st-card">
    <h2>Header &amp; hero text</h2>
    <p class="hint">Pick a shade for the header and a text colour for the hero. The header always follows the Dark / Light switch in the sidebar; the shade only changes its tint. Tip: with light hero text, raise the <b>Overlay</b> slider above so the photo is darker behind it.</p>

    <div class="opts">
      @php
        $hShade    = old('header_shade', $setting->headerShade());
        $hShadeDef = old('header_shade_default', $setting->headerShade() ? null : '1');
        $hDes      = old('header_design', $setting->headerDesign());
      @endphp
      <div>
        <span class="st-label">Header shade</span>
        
        <div class="hs-row">
          <input type="color" id="hsColor" name="header_shade" value="{{ $hShade ?: '#2563EB' }}">
          <label class="hs-def"><input type="checkbox" id="hsDef" name="header_shade_default" value="1" @checked($hShadeDef)> Use default shade</label>
        </div>
        <div class="hs-sw">
          @foreach(['#2563EB','#7C3AED','#DB2777','#DC2626','#EA580C','#059669','#0891B2','#475569'] as $sw)
            <button type="button" data-c="{{ $sw }}" style="background:{{ $sw }}" aria-label="Shade {{ $sw }}"></button>
          @endforeach
        </div>
        @error('header_shade')<div class="err">{{ $message }}</div>@enderror
        <script>
          (function(){
            var c=document.getElementById('hsColor'), d=document.getElementById('hsDef');
            document.querySelectorAll('.hs-sw button').forEach(function(b){
              b.addEventListener('click',function(){ c.value=b.dataset.c; d.checked=false; });
            });
            c.addEventListener('input',function(){ d.checked=false; });
          })();
        </script>
      </div>

      <div>
        <span class="st-label">Header design <small class="st-label-note">— click a name to see its sample</small></span>
        

        @php
          $designs = [
            'classic'   => ['Classic',   'Soft shaded bar with a glowing line underneath.'],
            'glass'     => ['Glass',     'Frosted, see-through bar that blurs the page behind it.'],
            'aurora'    => ['Aurora',    'Slow-moving colour glow drifting across the bar.'],
            'spotlight' => ['Spotlight', 'Clean bar with a bright spotlight and a coloured top edge.'],
          ];
        @endphp
        <div class="seg">
          @foreach($designs as $key => $d)
            <label><input type="radio" name="header_design" value="{{ $key }}" @checked($hDes === $key)><span>{{ $d[0] }}</span></label>
          @endforeach
        </div>

        {{-- only the sample of the chosen name is shown --}}
        <div class="hd-view" id="hdView">
          <div class="hd-stage">
            <div class="hd-demo hdr-{{ $hDes }}" id="hdDemo">
              <b class="hd-logo"><i></i>NMS Softwares</b>
              <span class="hd-links"><span>About</span><span>Services</span><span>Portfolio</span><span>Team</span><span>Blog</span></span>
              <em class="hd-btn">Contact Us</em>
            </div>
            <div class="hd-page"><i></i><i></i><i></i></div>
          </div>
          <div class="hd-info">
            <div class="hd-name" id="hdName">{{ $designs[$hDes][0] ?? 'Classic' }}</div>
            <div class="hd-desc" id="hdDesc">{{ $designs[$hDes][1] ?? '' }}</div>
          </div>
        </div>
        <script>
          (function(){
            var info=@json($designs), view=document.getElementById('hdView'), demo=document.getElementById('hdDemo'),
                nameEl=document.getElementById('hdName'), descEl=document.getElementById('hdDesc'),
                c=document.getElementById('hsColor'), d=document.getElementById('hsDef');
            function show(k){
              if(!info[k]) return;
              demo.className='hd-demo hdr-'+k; nameEl.textContent=info[k][0]; descEl.textContent=info[k][1];
              view.classList.remove('swap'); void view.offsetWidth; view.classList.add('swap');
            }
            document.querySelectorAll('input[name="header_design"]').forEach(function(r){
              r.addEventListener('change',function(){ if(r.checked) show(r.value); });
            });
            // the sample uses the header shade you pick above, live
            function shade(){ if(c&&d) view.style.setProperty('--hdr-shade', d.checked ? 'var(--nm-a)' : c.value); }
            if(c&&d){
              c.addEventListener('input',shade); d.addEventListener('change',shade);
              document.querySelectorAll('.hs-sw button').forEach(function(b){ b.addEventListener('click',function(){ setTimeout(shade,0); }); });
            }
            shade();
          })();
        </script>
        @error('header_design')<div class="err">{{ $message }}</div>@enderror
      </div>

      <div>
        <span class="st-label">Hero text colour</span>
        <div class="seg">
          <label><input type="radio" name="hero_text" value="dark"   @checked($hTxt === 'dark')><span>Dark text</span></label>
          <label><input type="radio" name="hero_text" value="light"  @checked($hTxt === 'light')><span>Light text</span></label>
          <label><input type="radio" name="hero_text" value="custom" @checked($hTxt === 'custom')><span>Custom</span></label>
        </div>
        <div class="hh-colour" id="hhColorBox">
          <input type="color" id="hhColor" name="hero_text_color" value="{{ $hCol }}">
          <code id="hhColorHex">{{ $hCol }}</code>
        </div>
        @error('hero_text')<div class="err">{{ $message }}</div>@enderror
        @error('hero_text_color')<div class="err">{{ $message }}</div>@enderror
      </div>
    </div>

    <div class="st-block">
      <span class="st-label">Live preview</span>
      <div class="hh-prev" id="hhPrev" @if($setting->bgImageUrl()) style="--hh-img:url('{{ $setting->bgImageUrl() }}');" @endif>
        <div class="hh-bar"><b>NMS <em>Softwares</em></b><span><i></i><i></i><i></i></span></div>
        <div class="hh-body">
          <small>CLOUD · AI · ENGINEERING</small>
          <h4>Transforming Ideas Into Digital Reality</h4>
          <p>We help businesses turn data into insights with survey reports and AI.</p>
        </div>
      </div>
    </div>
  </section>

  <div class="st-actions">
    <button type="submit" class="btn btn-primary">Save Settings</button>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline">Cancel</a>
  </div>
</form>

<script>
(function(){
  var prev = document.getElementById('bgPrev'), layer = document.getElementById('bgLayer'),
      none = document.getElementById('bgNone'), wash = document.getElementById('bgWash');
  var rV = document.getElementById('rVeil'), rB = document.getElementById('rBri'),
      rS = document.getElementById('rSat'), rBl = document.getElementById('rBlur');
  var oV = document.getElementById('oVeil'), oB = document.getElementById('oBri'),
      oS = document.getElementById('oSat'), oBl = document.getElementById('oBlur');

  function checked(name){ var r=document.querySelector('input[name="'+name+'"]:checked'); return r ? r.value : null; }

  function apply(){
    var fit = checked('bg_fit'), pos = checked('bg_position'), mode = checked('_mode') || 'hero';
    var veil = +rV.value / 100;

    layer.style.backgroundSize = fit==='contain' ? 'contain' : (fit==='stretch' ? '100% 100%' : 'cover');
    layer.style.backgroundPosition = 'center ' + (pos || 'center');
    layer.style.filter = 'blur('+rBl.value+'px) brightness('+rB.value+'%) saturate('+rS.value+'%)';

    if (mode === 'login') {                    /* login: photo fades into the dark background */
      prev.className = 'bg-prev login';
      layer.style.opacity = 1 - veil * 0.75;
      wash.style.opacity = 0;
    } else {                                   /* hero: a light wash sits over the photo */
      prev.className = 'bg-prev';
      layer.style.opacity = 1;
      wash.style.opacity = veil;
    }
    oV.textContent = rV.value + '%'; oB.textContent = rB.value + '%';
    oS.textContent = rS.value + '%'; oBl.textContent = rBl.value + 'px';
  }

  document.querySelectorAll('input[name="bg_fit"],input[name="bg_position"],input[name="_mode"]').forEach(function(el){ el.addEventListener('change', apply); });
  [rV,rB,rS,rBl].forEach(function(el){ el.addEventListener('input', apply); });

  document.getElementById('resetLook').addEventListener('click', function(){
    [rV,rB,rS,rBl].forEach(function(el){ el.value = el.dataset.default; });
    apply();
  });

  document.getElementById('bgInput').addEventListener('change', function(){
    var f = this.files[0]; if(!f) return;
    layer.style.backgroundImage = "url('" + URL.createObjectURL(f) + "')";
    layer.style.display = 'block'; if(none) none.style.display = 'none';
    document.getElementById('bgName').textContent = 'Selected: ' + f.name;
  });

  document.getElementById('logoInput').addEventListener('change', function(){
    var f = this.files[0]; if(!f) return;
    var box = document.getElementById('logoPrev'), empty = document.getElementById('logoEmpty');
    if(empty) empty.remove();
    var img = document.getElementById('logoImg');
    if(!img){ img = document.createElement('img'); img.id = 'logoImg'; box.appendChild(img); }
    img.src = URL.createObjectURL(f);
    document.getElementById('logoName').textContent = 'Selected: ' + f.name;
  });

  apply();
})();
</script>
<script>
(function(){
  var A = document.getElementById('thA'), B = document.getElementById('thB'),
      aHex = document.getElementById('thAHex'), bHex = document.getElementById('thBHex'),
      fH = document.getElementById('fHead'), fB = document.getElementById('fBody'),
      prev = document.getElementById('thPrev'), head = document.getElementById('thHead'), text = document.getElementById('thText');
  var DEF = {a:'#2563EB', b:'#0EA5E9', head:'Space Grotesk', body:'Inter'};
  var loaded = {};

  // Load a Google font just for the preview, only when it is picked
  function loadFont(name){
    if(loaded[name]) return; loaded[name] = true;
    var l = document.createElement('link'); l.rel = 'stylesheet';
    l.href = 'https://fonts.googleapis.com/css2?family=' + name.replace(/ /g,'+') + ':wght@400;700&display=swap';
    document.head.appendChild(l);
  }
  function paint(){
    prev.style.setProperty('--pa', A.value); prev.style.setProperty('--pb', B.value);
    aHex.textContent = A.value.toUpperCase(); bHex.textContent = B.value.toUpperCase();
    loadFont(fH.value); loadFont(fB.value);
    head.style.fontFamily = "'" + fH.value + "',sans-serif";
    text.style.fontFamily = "'" + fB.value + "',sans-serif";
  }

  A.addEventListener('input', paint); B.addEventListener('input', paint);
  fH.addEventListener('change', paint); fB.addEventListener('change', paint);

  document.querySelectorAll('.th-preset').forEach(function(btn){
    btn.addEventListener('click', function(){ A.value = btn.dataset.a; B.value = btn.dataset.b; paint(); });
  });
  document.getElementById('thReset').addEventListener('click', function(){
    A.value = DEF.a; B.value = DEF.b; fH.value = DEF.head; fB.value = DEF.body; paint();
  });
  paint();
})();
</script>
<script>
(function(){
  var prev = document.getElementById('hhPrev'), col = document.getElementById('hhColor'),
      box = document.getElementById('hhColorBox'), hex = document.getElementById('hhColorHex');

  function val(name){ var r = document.querySelector('input[name="'+name+'"]:checked'); return r ? r.value : null; }
  function isLight(h){ var n = parseInt(h.slice(1),16), r = n>>16&255, g = n>>8&255, b = n&255; return (0.299*r + 0.587*g + 0.114*b) > 150; }

  function paint(){
    var hs = val('header_style'), ht = val('hero_text'), c = col.value;
    var lightText = ht === 'light' || (ht === 'custom' && isLight(c));
    prev.classList.toggle('hdr-dark', hs === 'dark');
    prev.classList.toggle('tone-dark', lightText);
    box.style.display = ht === 'custom' ? 'flex' : 'none';
    hex.textContent = c.toUpperCase();
    prev.style.setProperty('--hh-text', ht === 'light' ? '#fff' : (ht === 'custom' ? c : '#0B1220'));
    prev.style.setProperty('--hh-sub',  ht === 'light' ? 'rgba(255,255,255,.88)' : (ht === 'custom' ? c : '#5B6474'));
  }

  document.querySelectorAll('input[name="header_style"],input[name="hero_text"]').forEach(function(el){ el.addEventListener('change', paint); });
  col.addEventListener('input', paint);
  paint();
})();
</script>
@endsection