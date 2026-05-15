const dotenv = require('dotenv');
const dotenvExpand = require('dotenv-expand');
const env = dotenv.config();
dotenvExpand.expand(env);
const path = require('path')
const fs = require('fs');

const args = process.argv
const blockName = args[2]
const prodDest = args[3]
const destNameRaw = args[4]
const prodDestName = `poeticsoft-heart-${destNameRaw}`

// Determinar la categoría basándonos en el nombre del plugin (ej: blocksbase -> base, blocksai -> ai)
const categorySuffix = destNameRaw.replace('blocks', '')
const blockCategory = `poeticsoft-heart-${categorySuffix || 'base'}`

const srcBuild = `${process.env.src}/common/block/build`
const destBuild = `${process.env[prodDest]}/${prodDestName}/blocks/${blockName}/build`
const srcBlockJson = `${process.env.src}/common/block/block.json`
const destBlockJson = `${process.env[prodDest]}/${prodDestName}/blocks/${blockName}/block.json`
const srcRender = `${process.env.src}/common/block/render.php`
const destRender = `${process.env[prodDest]}/${prodDestName}/blocks/${blockName}/render.php`

try {
  fs.cpSync(srcBuild, destBuild, { recursive: true })
} catch (error) {
  console.error('Hubo un error al copiar la carpeta:', error.message);
}

try {
  fs.cpSync(srcBlockJson, destBlockJson)
  
  // Leer y reemplazar variables en block.json
  let content = fs.readFileSync(destBlockJson, 'utf8')
  content = content.replace(/{{name}}/g, blockName)
  content = content.replace(/{{title}}/g, blockName.charAt(0).toUpperCase() + blockName.slice(1))
  content = content.replace(/{{description}}/g, `Bloque ${blockName} para el ecosistema Poeticsoft Heart.`)
  content = content.replace(/{{icon}}/g, 'admin-plugins')
  content = content.replace(/{{category}}/g, blockCategory)
  
  fs.writeFileSync(destBlockJson, content)
} catch (error) {
  console.error('Hubo un error al procesar block.json:', error.message);
}

try {
  fs.cpSync(srcRender, destRender)
} catch (error) {
  console.error('Hubo un error al copiar la carpeta:', error.message);
}