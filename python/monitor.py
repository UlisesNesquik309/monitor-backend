"""
monitor.py
Autor: (Jose Ulises Robledo Gutierrez)
Qué hace: lee en tiempo real el porcentaje de uso de CPU, RAM y disco
de la máquina donde corre, y lo imprime en formato JSON por stdout.
PHP ejecuta este script con shell_exec() y captura esa salida.
"""

import json
import psutil


def obtener_uso():
    cpu = psutil.cpu_percent(interval=1)          # % de uso de CPU
    ram = psutil.virtual_memory().percent          # % de uso de RAM
    disco = psutil.disk_usage('/host_c').percent         # % de uso de disco

    return {
        "cpu": round(cpu, 1),
        "ram": round(ram, 1),
        "disco": round(disco, 1)
    }


if __name__ == "__main__":
    datos = obtener_uso()
    print(json.dumps(datos))
