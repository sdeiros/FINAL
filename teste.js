const canvasContainer = document.getElementById('canvas-container');

// Função para carregar o modelo GLB
function loadGLBModel() {
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
    const renderer = new THREE.WebGLRenderer();
    renderer.setSize(window.innerWidth, window.innerHeight);
    canvasContainer.appendChild(renderer.domElement);

    // Localização do arquivo GLB
    const modelPath = '3D/navigating_the_dark.glb';

    // Loader para o arquivo GLB
    const loader = new THREE.GLTFLoader();
    loader.load(modelPath, function (gltf) {
        const model = gltf.scene;
        scene.add(model);
    });

    camera.position.z = 5;

    // Função para atualizar a cena
    function animate() {
        requestAnimationFrame(animate);
        renderer.render(scene, camera);
    }

    animate();
}

// Chama a função para carregar o modelo GLB
loadGLBModel();
