#!/bin/bash

# Mostrar estado actual
echo "🟢 Ejecutando git status..."
git status
echo

# Añadir todos los cambios
echo "🟢 Ejecutando git add ."
git add .
echo

# Preguntar tipo de cambio
echo "¿Qué tipo de cambio hiciste?"
echo "1) feat     - Nueva funcionalidad"
echo "2) fix      - Corrección de errores"
echo "3) docs     - Documentación"
echo "4) style    - Estilo, formato (sin cambios funcionales)"
echo "5) refactor - Refactorización"
echo "6) test     - Cambios en pruebas"
echo "7) chore    - Tareas varias"
read -p "Selecciona un número (1-7): " tipo_opcion

# Asignar tipo de cambio según la opción
case $tipo_opcion in
    1) tipo="feat" ;;
    2) tipo="fix" ;;
    3) tipo="docs" ;;
    4) tipo="style" ;;
    5) tipo="refactor" ;;
    6) tipo="test" ;;
    7) tipo="chore" ;;
    *) echo "Opción no válida. Cancelando..."; exit 1 ;;
esac

# Preguntar descripción del commit
read -p "🟢Escribe el mensaje del commit: " mensaje

# Ejecutar commit
echo "🟢 Ejecutando git commit..."
git commit -m "$tipo: $mensaje"

# Hacer push
echo "🟢 Haciendo push..."
git push

echo "🟢 ¡Listo!"
