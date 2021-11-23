import yaml
from pprint import pprint

from datetime import datetime, timedelta
import random

id_reactivo = 10

hoy = datetime.now()
def fecha_aleatoria():
    nueva_fecha = hoy - timedelta(
        days=random.randint(0, 10), 
        hours=random.randint(0, 23), 
        minutes=random.randint(0, 60))

    return nueva_fecha.strftime('%Y-%m-%d %H:%M')

def limpiar(s: str):
    return ' '.join(l.strip() for l in s.strip().splitlines())

def sanitizar(s: str):
    return s.replace("'", "''")

def id_tema(tema):
    d = {'Matemáticas': 1,
        'Español': 2
        }
    
    return d[tema]


with open(r'reactivos.yaml') as file:
    documents = yaml.full_load(file)
    i = 0

    for r in documents['reactivos']:
        parte1 = "INSERT INTO reactivo VALUES({}, True, 2, {}, '{}', ".format(id_reactivo + i, id_tema(r['tema']), fecha_aleatoria())
        parte2 = "'{}', '{}', {});\n".format(r['nivel'], sanitizar(limpiar(r['enunciado'])), r['multiple'])
        print(parte1 + parte2)

        i += 1

        for x in r['opciones']:
            print(x)
        

    # for reactivo in documents['reactivos']:
    #     for key, value in reactivo.items():
            
    #         if key == 'opciones':
    #             for x in value:
    #                 print(x)
    #         else: 
    #             print(value)


