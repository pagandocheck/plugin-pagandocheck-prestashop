# plugin-pagandocheck-prestashop
Plugin de Prestashop para pagos con Pagando Check.

## Instalar plugin de Pagando Check para pagos en la tienda.

## Requisitos previos

  - Tener una **cuenta** de **empresa** en **Pagando Check**.
  - Tener una tienda de Prestashop, si no la tiene, puede instalar nuestra tienda demo siguiendo las instrucciones de este [repositorio](https://github.com/pagandocheck/prestashop-store).
  - Tener permisos de administrador o poder realizar modificaciones o configuraciones del módulo de **Pagando Check**.
  - Ya que el módulo de **Pagando Check** se **utiliza** para que un **sitio web externo pueda procesar pagos**,
  se recomienda que este tipo de pago lo configure una persona con conocimientos técnicos.
  
### 1. Generar Llaves de Prueba

Para obtener sus llaves de prueba debe ingresar con su cuenta empresarial a https://negocios.pagando.mx

<img width="1266" alt="Captura de Pantalla 2021-08-11 a la(s) 13 40 38" src="https://user-images.githubusercontent.com/88348069/129092607-1e4b96f6-cd8e-4538-a9e0-d2094361eb47.png">

Una vez dentro, en el menú de opciones, dentro del apartado de pagos, ingresara a **API para sitio web**. Y luego hacer clic en **Botón Checkout**.

<img width="784" alt="Captura de Pantalla 2021-08-11 a la(s) 13 44 18" src="https://user-images.githubusercontent.com/88348069/129093055-57741a7a-3a67-4da6-a13b-0ca99a83fdf3.png">

Depués en la opción **Prestashop**, en la primera sección, podrá generar y recuperar sus llaves de prueba.

<img src="https://rapi-doc.s3.amazonaws.com/Captura+de+Pantalla+2021-08-03+a+la(s)+11.48.57.png" style="display: block; margin-left: auto; margin-right: auto;"/>

### 2. Configuración de módulo Checkout

Aquí se configura la dirección a donde quiere regresar a sus clientes una vez que se ha efectuado el pago, entre otras configuraciones.

<img src="https://rapi-doc.s3.amazonaws.com/Captura+de+Pantalla+2021-08-03+a+la(s)+11.45.21.png" style="display: block; margin-left: auto; margin-right: auto;"/>

### 3. Descarga del módulo

Para obtener el modulo debe clonar este proyecto de github en su equipo de computo con el siguiente comando:

```
git clone git@github.com:pagandocheck/plugin-pagandocheck-prestashop.git
```
Y posterioromente comprimir en un archivo .zip, la carpeta **plugin-pagandocheck-prestashop/ pagandopayment** que se encuentra dentro del proyecto descargado.

### 3. Instalación del módulo

Dentro de tu panel de administrador en PrestaShop, dirígete al apartado de **MÓDULOS** dentro de la sección **MODULE MANAGER**.
Una véz dentro da clic en el botón **SUBIR UN MÓDULO** y elige el archivo comprimido que hemos comprimido previamente.

<img src="https://staging-client.pagando.mx/img/catalogo-modulos.6e09a993.png"/>


### 4. Configuración del módulo

Una véz instalado nos dírijimos al **GESTOR DE MÓDULOS**, y en su buscador escribimos la palabra **Pagando**.
Una vez que aparezca el módulo, damos clic en **CONFIGURAR** .
 
<img src="https://staging-client.pagando.mx/img/busqueda-modulos.a3d00902.png"/>


### 5. Uso de credenciales

En la ventana de **CONFIGURACIÓN** rellenamos los campos de **USUARIO** y **CONTRASEÑA** con las credenciales que hemos obtenido.
Adicional a esto, elegimos la modalidad tipo **EXTERNAL**.

<img src="https://staging-client.pagando.mx/img/modulo-instalado.e1df2af9.png"/>


### 6. Visualización del método de pago

Después de finalizar con la configuración podrá visualizar su nuevo método de pago en su carrito de compras.

<img width="580" alt="Captura de Pantalla 2021-08-18 a la(s) 10 38 40" src="https://user-images.githubusercontent.com/88348069/129939887-b0976d74-9d54-4940-960e-583675be5944.png">

  
