import os

def renombrar_imagenes(ruta_carpeta, nombre_base=""):
    """
    Renombra imágenes en una carpeta específica
    
    Argumentos de la función:
        ruta_carpeta (str): Ruta de la carpeta con las imágenes (obligatorio)
        nombre_base (str): Nombre base para las imágenes (opcional)
    """
    
    # Verificar si la carpeta existe
    if not os.path.isdir(ruta_carpeta):
        print("Error: La carpeta no existe.")
        return False

    # Obtener lista de archivos de imagen
    extensiones_validas = ('.jpg', '.jpeg', '.png', '.gif', '.bmp', '.tiff', '.webp')
    archivos = [f for f in os.listdir(ruta_carpeta) 
               if f.lower().endswith(extensiones_validas)]

    if not archivos:
        print("No se encontraron imágenes en la carpeta.")
        return False

    # Ordenar archivos numéricamente
    try:
        archivos.sort(key=lambda x: int(''.join(filter(str.isdigit, x)) or 0))
    except ValueError:
        print("Advertencia: No se pudieron ordenar numéricamente, usando orden alfabético.")
        archivos.sort()

    # Renombrar archivos
    contador = 59
    for archivo in archivos:
        extension = os.path.splitext(archivo)[1]
        
        # No siempere se ocupa el nombre base entonces es opcional
        if nombre_base:
            nuevo_nombre = f"{nombre_base}{contador}{extension}"
        else:
            nuevo_nombre = f"{contador}{extension}"
        
        vieja_ruta = os.path.join(ruta_carpeta, archivo)
        nueva_ruta = os.path.join(ruta_carpeta, nuevo_nombre)
        
        # Evitar sobrescribir archivos existentes
        if not os.path.exists(nueva_ruta):
            os.rename(vieja_ruta, nueva_ruta)
            print(f"Renombrado: {archivo} -> {nuevo_nombre}")
            contador += 1
        else:
            print(f"Error: {nuevo_nombre} ya existe. Saltando...")
    
    return True

def main():
    # Solicitar la ruta de la carpeta (obligatorio)
    ruta_carpeta = input("Introduce la ruta completa de la carpeta con las imágenes: ")
    
    # Solicitar el nombre base (opcional)
    nombre_base = input("Introduce el nuevo nombre base para las imágenes (opcional, presiona Enter para omitir): ")
    
    # Llamar a la función con los parámetros
    renombrar_imagenes(ruta_carpeta, nombre_base)

# Función para uso programático
def renombrar_imagenes_directo(ruta_carpeta, nombre_base=""):
    """
    Versión directa para usar en otros scripts sin interacción de usuario
    
    Args:
        ruta_carpeta (str): Ruta de la carpeta con las imágenes (obligatorio)
        nombre_base (str): Nombre base para las imágenes (opcional)
    
    Returns:
        bool: True si fue exitoso, False si hubo error
    """
    return renombrar_imagenes(ruta_carpeta, nombre_base)

if __name__ == "__main__":
    main()