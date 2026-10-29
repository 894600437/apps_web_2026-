const fecha=document.getElementById("fecha");
const horaEntrada=document.getElementById("horaEntrada");
const horaSalida=document.getElementById("horaSalida");
const totalEstimado=document.getElementById("totalEstimado");
const btnReservar=document.getElementById("btnReservar");
const PRECIO_HORA=20;
function establecerFecha(){
const hoy=new Date();
const anio=hoy.getFullYear();
const mes=String(hoy.getMonth()+1).padStart(2,"0");
const dia=String(hoy.getDate()).padStart(2,"0");
fecha.value=${anio}-${mes}-${dia};
fecha.min=${anio}-${mes}-${dia};
}
function calcularHoras(){
if(!horaEntrada.value||!horaSalida.value){
totalEstimado.textContent="$0 MXN";
return;
}
const entrada=horaEntrada.value.split(":");
const salida=horaSalida.value.split(":");
const minutosEntrada=parseInt(entrada[0])*60+parseInt(entrada[1]);
const minutosSalida=parseInt(salida[0])*60+parseInt(salida[1]);
if(minutosSalida<=minutosEntrada){
totalEstimado.textContent="$0 MXN";
return;
}
const minutosTotales=minutosSalida-minutosEntrada;
const horas=Math.ceil(minutosTotales/60);
const total=horas*PRECIO_HORA;
totalEstimado.textContent=$${total} MXN;
}
horaEntrada.addEventListener("change",calcularHoras);
horaSalida.addEventListener("change",calcularHoras);
btnReservar.addEventListener("click",function(){
if(!fecha.value){
alert("Selecciona una fecha para continuar.");
return;
}
if(!horaEntrada.value||!horaSalida.value){
alert("Selecciona la hora de entrada y salida.");
return;
}
const entrada=horaEntrada.value.split(":");
const salida=horaSalida.value.split(":");
const minutosEntrada=parseInt(entrada[0])*60+parseInt(entrada[1]);
const minutosSalida=parseInt(salida[0])*60+parseInt(salida[1]);
if(minutosSalida<=minutosEntrada){
alert("La hora de salida debe ser posterior a la hora de entrada.");
return;
}
document.getElementById("confirmFecha").textContent=fecha.value;
document.getElementById("confirmEntrada").textContent=horaEntrada.value;
document.getElementById("confirmSalida").textContent=horaSalida.value;
document.getElementById("confirmTotal").textContent=totalEstimado.textContent;
const modal=new bootstrap.Modal(document.getElementById("modalReserva"));
modal.show();
});
document.addEventListener("DOMContentLoaded",function(){
establecerFecha();
calcularHoras();
});