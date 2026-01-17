<!DOCTYPE html>
<html lang="sr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>FitScore </title>

<style>

* { box-sizing: border-box; margin:0; padding:0; }
body { background:#1e1e1e; font-family: Arial,sans-serif; height:100vh; display:flex; justify-content:center; align-items:center; }

.wizard { position:relative; width:650px; height:500px; }

.step { position:absolute; inset:0; background:#fff; border-radius:20px; padding:40px; text-align:center; opacity:0; pointer-events:none; transform:translateY(20px); transition: all 0.4s ease; }
.step.active { opacity:1; pointer-events:auto; transform:translateY(0); }

.logo { font-size:26px; font-weight:bold; margin-bottom:20px; }
.logo span { color:#e53935; }

.option { background:#e0e0e0; border-radius:25px; padding:12px 20px; margin:12px 0; display:flex; justify-content:space-between; align-items:center; cursor:pointer; user-select:none; }
.input-row { margin:15px 0; text-align:left; }
.input-row input { width:100%; padding:12px; border-radius:20px; border:none; background:#e0e0e0; }

.actions { display:flex; justify-content:space-between; margin-top:25px; }
button { border:none; border-radius:10px; padding:10px 25px; font-size:15px; cursor:pointer; }
.back { background:#ff3b3b; color:white; }
.next { background:#ff3b3b; color:white; }

.progress { position:absolute; bottom:20px; left:40px; right:40px; height:8px; background:#ccc; border-radius:10px; overflow:hidden; }
.progress-fill { height:100%; width:0%; background:#c62828; transition: width 0.4s ease; }

.option.invalid, .input-row.invalid input { outline:2px solid #ff3b3b; }
.option.valid, .input-row.valid input { outline:2px solid #4caf50; }

.error-msg { color:#ff3b3b; font-size:13px; margin-top:5px; display:none; text-align:left; transition:opacity 0.3s ease; }
body {
    background: url("images/background.jpg");
    background-size: cover;
    font-family: Arial, sans-serif;
    height: 100vh;
    display: flex;
}
</style>
</head>
<body>

<form class="wizard" method="POST" action="ubazu.php">
    <!-- STEP 0 -->
    <div class="step active">
        <div class="logo"><img src="../images/image.png" height="50px"></div>
        <h2>Hajde da počnemo 💪</h2>
        <p style="margin:20px 0;font-size:17px;">Odgovorite na nekoliko kratkih pitanja kako bismo napravili plan baš po vašoj meri.</p>
        <div class="actions" style="justify-content:center;">
            <button type="button" class="next" onclick="next()">Započni</button>
        </div>
    </div>

    <!-- STEP 1 -->
    <div class="step">
        <div class="logo"><img src="../images/image.png" height="40px"></div>
        <h2>Šta želite da postignete?</h2>
        <div class="option">
        Izgubim kilograme
        <input type="checkbox" name="ciljevi[]" value="mršavljenje">
        </div>

        <div class="option">
        Nabacim mišićnu masu
        <input type="checkbox" name="ciljevi[]" value="masa">
        </div>

        <div class="option">
        Ostanem u formi
        <input type="checkbox" name="ciljevi[]" value="forma">
        </div>

        <div class="option">
        Budem aktivniji/ja
        <input type="checkbox" name="ciljevi[]" value="aktivnost">
        </div>

        <div class="option">
        Hranim se zdravije
        <input type="checkbox" name="ciljevi[]" value="ishrana">
        </div>
        <div class="actions">
            <button type="button" class="back" onclick="back()">Vrati se nazad</button>
            <button type="button" class="next" onclick="next()">Nastavi</button>
        </div>
    </div>

    <!-- STEP 2 -->
    <div class="step">
        <div class="logo"><img src="../images/image.png" height="50px"></div>
        <h2>Koliko ste trenutno fizički aktivni?</h2>
        <div class="option">Ne vežbam <input type="radio" name="aktivnost" value="0"></div>
        <div class="option">Povremeno <input type="radio" name="aktivnost" value="1-2"></div>
        <div class="option">Redovno <input type="radio" name="aktivnost" value="3-5"></div>
        <div class="option">Veoma aktivan <input type="radio" name="aktivnost" value="6+"></div>
        <div class="actions">
            <button type="button" class="back" onclick="back()">Vrati se nazad</button>
            <button type="button" class="next" onclick="next()">Nastavi</button>
        </div>
    </div>

    <!-- STEP 3 -->
    <div class="step">
        <div class="logo"><img src="../images/image.png" height="50px"></div>
        <h2>Kakvo je vaše iskustvo sa treninzima?</h2>
        <div class="option">Početnik <input type="radio" name="iskustvo" value="pocetnik"></div>
        <div class="option">Srednji <input type="radio" name="iskustvo" value="srednji"></div>
        <div class="option">Napredni <input type="radio" name="iskustvo" value="napredni"></div>
        <div class="actions">
            <button type="button" class="back" onclick="back()">Vrati se nazad</button>
            <button type="button" class="next" onclick="next()">Nastavi</button>
        </div>
    </div>

    <!-- STEP 4 -->
    <div class="step">
        <div class="logo"><img src="../images/image.png" height="50px"></div>
        <h2>Unesite osnovne podatke</h2>
        <div class="input-row">
            <label>Datum rođenja</label>
            <input type="date" name="datum_rodjenja">
        </div>
        <div class="input-row">
            <label>Visina (cm)</label>
            <input type="number" name="visina">
        </div>
        <div class="input-row">
            <label>Težina (kg)</label>
            <input type="number" name="tezina">
        </div>
        <div class="actions">
            <button type="button" class="back" onclick="back()">Vrati se nazad</button>
            <button type="button" class="next" onclick="next()">Nastavi</button>
        </div>
    </div>

    <!-- STEP 5 -->
    <div class="step">
        <div class="logo"><img src="../images/image.png" height="50px"></div>
        <h2>Izaberite pol</h2>
        <div class="option">Muški <input type="radio" name="pol" value="M"></div>
        <div class="option">Ženski <input type="radio" name="pol" value="Ž"></div>
        <div class="actions">
            <button type="button" class="back" onclick="back()">Vrati se nazad</button>
            <button type="submit" class="next">Završi</button>
        </div>
    </div>

    <div class="progress">
        <div class="progress-fill" id="bar"></div>
    </div>
</form>

<script>
let index = 0;
const steps = document.querySelectorAll(".step");
const bar = document.getElementById("bar");

function update() {
    steps.forEach(s => s.classList.remove("active"));
    steps[index].classList.add("active");
    bar.style.width = (index/(steps.length-1)*100) + "%";
}

// VALIDACIJA I PORUKE
function validateStep() {
    const currentStep = steps[index];
    let valid = true;

    let msg = currentStep.querySelector('.error-msg');
    if(!msg){
        msg = document.createElement('div');
        msg.classList.add('error-msg');
        currentStep.appendChild(msg);
    }

    // STEP 1 - checkbox
    if(index===1){
        const checkboxes=currentStep.querySelectorAll('input[type="checkbox"]');
        const anyChecked=Array.from(checkboxes).some(cb=>cb.checked);
        if(!anyChecked){
            valid=false;
            checkboxes.forEach(cb=>cb.parentElement.classList.add('invalid'));
            checkboxes.forEach(cb=>cb.parentElement.classList.remove('valid'));
            msg.innerText="Molimo izaberite barem jednu opciju.";
            msg.style.display="block";
        } else {
            checkboxes.forEach(cb=>{
                cb.parentElement.classList.remove('invalid');
                if(cb.checked) cb.parentElement.classList.add('valid');
                else cb.parentElement.classList.remove('valid');
            });
            msg.style.display="none";
        }
    }

    // STEP 2,3,5 - radio
    if([2,3,5].includes(index)){
        const radios=currentStep.querySelectorAll('input[type="radio"]');
        const checked=Array.from(radios).some(r=>r.checked);
        if(!checked){
            valid=false;
            radios.forEach(r=>r.parentElement.classList.add('invalid'));
            radios.forEach(r=>r.parentElement.classList.remove('valid'));
            msg.innerText="Molimo izaberite opciju.";
            msg.style.display="block";
        } else {
            radios.forEach(r=>{
                r.parentElement.classList.remove('invalid');
                if(r.checked) r.parentElement.classList.add('valid');
                else r.parentElement.classList.remove('valid');
            });
            msg.style.display="none";
        }
    }

    // STEP 4 - input
    if(index===4){
        const inputs=currentStep.querySelectorAll('input');
        let allFilled=true;
        inputs.forEach(input=>{
            if(!input.value){
                allFilled=false;
                input.parentElement.classList.add('invalid');
                input.parentElement.classList.remove('valid');
            } else {
                input.parentElement.classList.remove('invalid');
                input.parentElement.classList.add('valid');
            }
        });
        if(!allFilled){
            valid=false;
            msg.innerText="Molimo popunite sva polja.";
            msg.style.display="block";
        } else msg.style.display="none";
    }

    return valid;
}

function next(){ if(!validateStep()) return; if(index<steps.length-1){index++;update();} }
function back(){ if(index>0){index--;update();} }   

// klik na celu opciju
document.querySelectorAll('.option').forEach(opt=>{
    const input=opt.querySelector('input');
    opt.addEventListener('click', e=>{
        if(e.target.tagName!=='INPUT'){
            if(input.type==='checkbox') input.checked=!input.checked;
            else if(input.type==='radio') input.checked=true;
            input.dispatchEvent(new Event('change'));
        }
    });
});

// promene outline i nestanak zelenog kada nije selektovano
document.querySelectorAll('.option input').forEach(input=>{
    input.addEventListener('change', ()=>{
        if(input.type === 'radio'){
            // ukloni valid sa svih u istoj grupi
            const radios = document.querySelectorAll(`input[name="${input.name}"]`);
            radios.forEach(r => r.parentElement.classList.remove('valid', 'invalid'));
            // postavi valid samo za selektovano
            if(input.checked) input.parentElement.classList.add('valid');
        } else { // checkbox
            if(input.checked){
                input.parentElement.classList.add('valid');
                input.parentElement.classList.remove('invalid');
            } else input.parentElement.classList.remove('valid');
        }
    });
});


document.querySelectorAll('.input-row input').forEach(input=>{
    input.addEventListener('input', ()=>{
        if(input.value){
            input.parentElement.classList.add('valid');
            input.parentElement.classList.remove('invalid');
        } else input.parentElement.classList.remove('valid');
    });
});

update();
</script>

</body>
</html>
