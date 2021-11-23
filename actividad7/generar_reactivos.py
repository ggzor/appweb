import yaml
import textwrap

from datetime import datetime, timedelta
import random

id_reactivo = 10
id_opcion = 35

hoy = datetime.now()


def fecha_aleatoria():
    nueva_fecha = hoy - timedelta(
        days=random.randint(0, 10),
        hours=random.randint(0, 23),
        minutes=random.randint(0, 60),
    )

    return nueva_fecha.strftime("%Y-%m-%d %H:%M")


def limpiar(s: str):
    return " ".join(l.strip() for l in s.strip().splitlines())


def sanitizar(s: str):
    return s.replace("'", "''")


def id_tema(tema):
    d = {"Matemáticas": 1, "Español": 2}

    return d[tema]


with open(r"reactivos.yaml") as file:
    documents = yaml.full_load(file)

    for i, reactivo in enumerate(documents["reactivos"]):
        insert_reactivo = f"""\
            INSERT INTO reactivo VALUES
                ( {id_reactivo + i}
                , True
                , 2
                , {id_tema(reactivo['tema'])}
                , '{fecha_aleatoria()}'
                , '{reactivo['nivel']}'
                , '{sanitizar(limpiar(reactivo['enunciado']))}'
                , {reactivo['multiple']}
                );
            """

        print(textwrap.dedent(insert_reactivo))

        for opciones in reactivo["opciones"]:
            insert_opcion = f"""\
                INSERT INTO opcion (id_reactivo, correcta, contenido) VALUES 
                    ( {id_reactivo + i}
                    , {opciones[0]}
                    , '{opciones[1]}'
                    );
                """

            print(textwrap.dedent(insert_opcion))
