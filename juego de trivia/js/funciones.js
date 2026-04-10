let datosTrivia;
let boton = document.getElementById("btnIniciar");
let select = document.getElementById("categoria");

let preguntasSeleccionadas = [];
let preguntaIndex = 0;
let tiempo;
let intervalo;

let correctas = 0;
let incorrectas = 0;
let noRespondidas = 0;

fetch("trivia_realista_240.json")
  .then(res => res.json())
  .then(data => { datosTrivia = data; })
  .catch(err => console.error(err));

boton.addEventListener("click", () => {
    const categoriaElegida = select.value;
    if (!categoriaElegida) { 
        document.getElementById("mensajeGeneral").textContent = "Debes elegir una categoría";
        return; 
    }
    iniciarTrivia(categoriaElegida);
});

function iniciarTrivia(categoriaElegida) {
    correctas = 0;
    incorrectas = 0;
    noRespondidas = 0;
    actualizarPuntaje();

    const categoria = datosTrivia.categorias.find(cat =>
        cat.nombre.toLowerCase() === categoriaElegida.toLowerCase()
    );
    if (!categoria) { 
        document.getElementById("mensajeGeneral").textContent = "Categoría no encontrada";
        return; 
    }

    const preguntas = categoria.preguntas;

    preguntasSeleccionadas = [];
    let usados = new Set();
    while (preguntasSeleccionadas.length < 5) {
        let idx = Math.floor(Math.random() * preguntas.length);
        if (!usados.has(idx)) {
            usados.add(idx);
            preguntasSeleccionadas.push(preguntas[idx]);
        }
    }

    preguntaIndex = 0;
    mostrarPreguntaActual();
}

function mostrarPreguntaActual() {
    if (preguntaIndex >= preguntasSeleccionadas.length) {
        document.getElementById("textoPregunta").textContent = "Se terminaron las preguntas!";
        document.getElementById("mensajeRespuesta").textContent = "";
        document.getElementById("contador").textContent = "";
        
        for (let i = 1; i <= 4; i++) document.getElementById(`op${i}`).disabled = true;
        return;
    }

    const pregunta = preguntasSeleccionadas[preguntaIndex];
    document.getElementById("textoPregunta").textContent = pregunta.pregunta;
    document.getElementById("mensajeRespuesta").textContent = "";

    const opciones = [pregunta.correcta, ...pregunta.incorrectas];
    for (let i = opciones.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [opciones[i], opciones[j]] = [opciones[j], opciones[i]];
    }

    for (let i = 0; i < 4; i++) {
        const btn = document.getElementById(`op${i+1}`);
        btn.textContent = opciones[i];
        btn.disabled = false;
        btn.onclick = () => {
            clearInterval(intervalo);
            
            for (let j = 0; j < 4; j++) document.getElementById(`op${j+1}`).disabled = true;
            evaluarRespuesta(opciones[i], pregunta.correcta);
        };
    }

    tiempo = 5;
    document.getElementById("contador").textContent = `Tiempo: ${tiempo}s`;
    clearInterval(intervalo);
    intervalo = setInterval(() => {
        tiempo--;
        document.getElementById("contador").textContent = `Tiempo: ${tiempo}s`;
        if (tiempo <= 0) {
            clearInterval(intervalo);
            document.getElementById("mensajeRespuesta").textContent = `No respondiste. La correcta era: ${pregunta.correcta}`;
        
            for (let i = 0; i < 4; i++) document.getElementById(`op${i+1}`).disabled = true;
            noRespondidas++;
            actualizarPuntaje();
    
            setTimeout(() => {
                preguntaIndex++;
                mostrarPreguntaActual();
            }, 1000);
        }
    }, 1000);
}

function evaluarRespuesta(opcionSeleccionada, correcta) {
    if (opcionSeleccionada === correcta) {
        document.getElementById("mensajeRespuesta").textContent = "Correcto!";
        correctas++;
    } else {
        document.getElementById("mensajeRespuesta").textContent = `Incorrecto. La correcta era: ${correcta}`;
        incorrectas++;
    }

    actualizarPuntaje();

    setTimeout(() => {
        preguntaIndex++;
        mostrarPreguntaActual();
    }, 1000);
}

function actualizarPuntaje() {
    document.getElementById("puntajeCorrectas").textContent = `Correctas: ${correctas}`;
    document.getElementById("puntajeIncorrectas").textContent = `Incorrectas: ${incorrectas}`;
    document.getElementById("puntajeNoRespondidas").textContent = `No respondidas: ${noRespondidas}`;
}