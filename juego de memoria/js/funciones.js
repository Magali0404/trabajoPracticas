let botonIniciar = document.getElementById("btnIniciar");
let botonConfirmar = document.getElementById("btnConfirmar");
let mostrar = document.getElementById("numero");
let inputRespuesta = document.getElementById("respuesta");
let resultado = document.getElementById("resultado");
let inputCantidad = document.getElementById("cantidad");
let inputTiempo = document.getElementById("tiempo");

let intervalo;
let numeros = [];
let contador = 0;
let suma = 0;
let tiempoDeRespuesta;

botonIniciar.addEventListener("click", iniciarJuego);

function iniciarJuego() {
    clearInterval(intervalo);
    clearTimeout(tiempoDeRespuesta);
    inputRespuesta.value = "";
    resultado.textContent = "";
    contador = 0;
    numeros = [];

    do {
        numeros = [];
        suma = 0;

        let cantidad = Number(inputCantidad.value);

        for (let i = 0; i < cantidad; i++) {
        let num;

        do {
            num = Math.floor(Math.random() * 31) - 10;
        } while (numeros.includes(num));

        numeros.push(num);
        suma += num;
        }

    } while (suma < 0);

    console.log("Secuencia generada:", numeros);
    console.log("Respuesta correcta:", suma);

    let tiempo = Number(inputTiempo.value);

    intervalo = setInterval(() => {
        mostrar.textContent = numeros[contador];
        contador++;

        if (contador === numeros.length) {
            clearInterval(intervalo);

            setTimeout(() => {
                mostrar.textContent = "";
                
                resultado.textContent = "Tenes 5 segundos para responder...";

                tiempoDeRespuesta=setTimeout(() => {
                    resultado.textContent = "Tiempo agotado. La suma era: " + suma;
                }, 5000);
                
            }, tiempo);
        }

    }, tiempo);
}

botonConfirmar.addEventListener("click", confirmacion);

function confirmacion() {
    clearTimeout(tiempoDeRespuesta);
    let respuestaUsuario = Number(inputRespuesta.value);

    if (respuestaUsuario === suma) {
        resultado.textContent = "Respuesta correcta!";
    } else {
        resultado.textContent = "Respuesta incorrecta. La suma era: " + suma;
    }
}