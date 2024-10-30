// src/utils/loadFonts.js

import pdfMake from 'pdfmake/build/pdfmake';

// Función para cargar y convertir las fuentes a Base64
export async function loadFontsToBase64() {
  const fontFiles = import.meta.glob('/resources/fonts/Roboto-*.ttf', { as: 'url' });

  const fontUrls = {
    normal: await fontFiles['/resources/fonts/Roboto-Regular.ttf'](),
    bold: await fontFiles['/resources/fonts/Roboto-Bold.ttf'](),
  };

  async function arrayBufferToBase64(url) {
    const response = await fetch(url);
    if (!response.ok) throw new Error(`Error al cargar la fuente: ${response.statusText}`);
    const buffer = await response.arrayBuffer();
    const blob = new Blob([buffer]);
    const reader = new FileReader();
    return new Promise((resolve) => {
      reader.onloadend = () => resolve(reader.result.split(',')[1]);
      reader.readAsDataURL(blob);
    });
  }

  pdfMake.vfs = {
    'Roboto-Regular.ttf': await arrayBufferToBase64(fontUrls.normal),
    'Roboto-Bold.ttf': await arrayBufferToBase64(fontUrls.bold)
  };

  pdfMake.fonts = {
    Roboto: {
      normal: 'Roboto-Regular.ttf',
      bold: 'Roboto-Bold.ttf'
    }
  };

  console.log('Fuentes cargadas y convertidas a Base64 correctamente.');
}
