class Carta {
    constructor(emoji) {
        this.emoji = emoji;
        this.descubierta = false;
        this.resuelta = false;

        this.elemento = document.createElement("div");
        this.elemento.classList.add("carta");
        this.elemento.textContent = "";
    }

    mostrar() {
        this.elemento.textContent = this.emoji;
        this.descubierta = true;
    }

    ocultar() {
        if (!this.resuelta) {
            this.elemento.textContent = "";
            this.descubierta = false;
        }
    }

    marcarResuelta() {
        this.resuelta = true;
    }
}

class Juego {
    constructor(filas, columnas) {
        this.filas = filas;
        this.columnas = columnas;
        this.tableroHTML = document.getElementById("tablero");

        this.intentos = 0;

        this.intentosHTML = document.createElement("p");
        this.intentosHTML.textContent = "Intentos: 0";
        document.body.insertBefore(this.intentosHTML, this.tableroHTML);

        this.tiempo = 0;

        this.tiempoHTML = document.createElement("p");
        this.tiempoHTML.textContent = "Tiempo: 0s";
        document.body.insertBefore(this.tiempoHTML, this.tableroHTML);

        this.iniciado = false;
        
        this.emojis = [
            "🍎","🍏","🍐","🍊","🍋","🍌","🍉","🍇","🍓","🍒",
            "🍈","🍒","🍑","🥭","🍍","🥥","🥝","🍅","🥑","🍋",
            "🍆","🥔","🍕","🌽","🌶️","🍏","🥒","🥬","🥦","🧄",
            "🧅","🍄","🥜","🌰","🍞","🥐","🥖","🥨","🥞","🧇",
            "🧀","🍖","🍗","🥩","🥓","🍔","🍟","🍕","🌭","🥪"
        ];

        this.cartas = [];
        this.primera = null;
        this.segunda = null;
        this.bloqueado = false;
        this.pares = 0;

        this.inicializar();
    }

    inicializar() {
        const totalCartas = this.filas * this.columnas;
        const totalPares = totalCartas / 2;

        let seleccion = this.emojis.slice(0, totalPares);

        let mezcla = [...seleccion, ...seleccion];

        mezcla.sort(() => Math.random() - 0.5);

        mezcla.forEach(emoji => {
            const carta = new Carta(emoji);

            carta.elemento.addEventListener("click", () => this.clickCarta(carta));

            this.cartas.push(carta);
            this.tableroHTML.appendChild(carta.elemento);
        });
    }

    clickCarta(carta) {
        if (!this.iniciado) {
            this.iniciado = true;

            this.intervalo = setInterval(() => {
                this.tiempo++;
                this.tiempoHTML.textContent = "Tiempo: " + this.tiempo + "s";
            }, 1000);
        }

        if (this.bloqueado) return;
        if (carta.descubierta || carta.resuelta) return;

        carta.mostrar();

        if (!this.primera) {
            this.primera = carta;
        } else {
            this.segunda = carta;
            this.verificar();
        }
    }

    verificar() {
        this.intentos++;
        this.intentosHTML.textContent = "Intentos: " + this.intentos;
        
        if (this.primera.emoji === this.segunda.emoji) {
            this.primera.marcarResuelta();
            this.segunda.marcarResuelta();

            this.pares++;

            this.resetTurno();

            if (this.pares === this.cartas.length / 2) {
                this.mostrarMensajeFinal();
            }

        } else {
            this.bloqueado = true;

            setTimeout(() => {
                this.primera.ocultar();
                this.segunda.ocultar();

                this.resetTurno();
                this.bloqueado = false;
            }, 1000);
        }
    }

    resetTurno() {
        this.primera = null;
        this.segunda = null;
    }

    mostrarMensajeFinal() {
        const mensaje = document.createElement("h2");
        if (this.intervalo) {
            clearInterval(this.intervalo);
        }
        mensaje.textContent = `Juego terminado en ${this.intentos} intentos y ${this.tiempo} segundos`;
        mensaje.style.textAlign = "center";
        mensaje.style.marginTop = "20px";

        document.body.appendChild(mensaje);
    }
}
const juego = new Juego(10, 10);