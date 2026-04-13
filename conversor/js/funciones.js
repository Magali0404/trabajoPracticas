document.getElementById("btnCalcular").addEventListener("click", calcular);

function calcular() {
    const input = document.getElementById("monto");
    const monto = parseFloat(input.value);
    const resultado = document.getElementById("resultado");
    const tabla = document.getElementById("tablaResultado");

    if (input.value === "") {
        resultado.textContent = "El campo está vacío";
        tabla.innerHTML = "";
        return;
    }

    if (isNaN(monto)) {
        resultado.textContent = "Ingrese un número válido";
        tabla.innerHTML = "";
        return;
    }

    if (monto <= 0) {
        resultado.textContent = "El monto debe ser mayor a 0";
        tabla.innerHTML = "";
        return;
    }

    fetch("https://dolarapi.com/v1/dolares")
        .then(function(response) {
            if (!response.ok) {
                throw new Error("Error en la API");
            }
            return response.json();
        })
        .then(function(data) {

            tabla.innerHTML = "";

            data.forEach(function(dolar) {
                if (
                    dolar.nombre === "Oficial" ||
                    dolar.nombre === "Blue" ||
                    dolar.nombre === "Bolsa"
                ) {

                    let usdCompra = (monto / dolar.compra).toLocaleString("es-AR", {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });

                    let usdVenta = (monto / dolar.venta).toLocaleString("es-AR", {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });

                    let fila = `
                        <tr>
                            <td>${dolar.nombre}</td>
                            <td>$${dolar.compra.toLocaleString("es-AR")}</td>
                            <td>$${dolar.venta.toLocaleString("es-AR")}</td>
                            <td>${usdCompra}</td>
                            <td>${usdVenta}</td>
                        </tr>
                    `;

                    tabla.innerHTML += fila;
                }
            });

            resultado.textContent = `Cálculo realizado para $${monto.toLocaleString("es-AR")}`;
        })
        .catch(function(error) {
            resultado.textContent = "Error al obtener datos de la API";
            tabla.innerHTML = "";
            console.log(error);
        });
}