# 16. [MongoBD] Exploración Visual de Datos con MongoDB Compass

## Preparar la Conexión desde Compass

### Comprobamos que tenemos instalado correctamente mongobd

sudo systemctl status mongod
Y nos conectamos en local:
mongodb://localhost:27017

## Exploración de la Biblioteca de Datos

Creamos una base de datos
Le ponemos el nombre que querrámos en este caso: fed_records
Un nombre de la colección inicial: planets
Y lo creamos con toda esta información

## Inserción de Datos Científicos

Insertaremos los datos de una forma visual  pulsando insert documents y le ponemos esto:
{
  "name": "Vulcan",
  "species": "Vulcans",
  "affiliation": "Federation",
  "warp_capable": true
}
Y le damos a guradar, repetiremos esta misma acción con las próximas dos:
{
  "name": "Qo'noS",
  "species": "Klingons",
  "affiliation": "Klingon Empire",
  "warp_capable": true
}
{
  "name": "Ferenginar",
  "species": "Ferengi",
  "affiliation": "Ferengi Alliance",
  "warp_capable": true
}

## Visualización y Edición de Documentos

Editamos un documento pulsando edit document
Cambiamos warp_capable de true a false y lo guardamos
Luego eliminaremos uno pulsando el delete

## Consultas Visuales con Filtros

Para buscar simplemente en el buscador de arriba pulsamos filtro visual y escribimos:
{ "affiliation": "Federation" }

## Agregaciones en la Sala de Análisis

Crearemos pipelines de analisis, entrando en aggregation y añadiendo una etapa $group
{
  "$group": {
    "_id": "$affiliation",
    "total": { "$sum": 1 }
  }
}
Después le dámos a run


## Inferir Esquemas

Simplemente entramos en la pestaña de schemas y nos mostrará los datos

## Crear Índices

Vamos a la pestaña de Indexes y le damos a create
Field: name
Sort: Ascending

## Desconexión y Cierre de Misión

Lo cerraremos con:
sudo systemctl stop mongod
