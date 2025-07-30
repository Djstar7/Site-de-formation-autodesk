<template>
    <div class="bg-white text-gray-800">
        <header class="bg-yellow-500">
            <div class="container mx-auto text-center">
                <h1 class="text-4xl font-bold text-white">Bienvenue sur notre site</h1>
                <p class="text-lg mt-2 text-gray-800">Découvrez nos plans de maisons et nos cours de formation</p>
            </div>
        </header>

        <main class="container mx-auto py-12 p-6">
            <section class="mb-12">
                <h2 class="text-3xl font-semibold text-center mb-6">Nos Plans de Maisons</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white text-gray-800 rounded-lg shadow-lg overflow-hidden transform hover:scale-105 transition duration-300">

                        <div class="h-64">
                            <img src="../../../../public/assets/images/image1Moderne.png" alt="Plan Moderne" class="w-full h-full object-cover">
                        </div>
                        <div class="p-4">
                            <h3 class="text-xl font-bold">Plan Moderne</h3>
                            <p class="mt-2">Un design moderne et élégant pour votre maison.</p>
                        </div>
                    </div>
                    <div class="bg-white text-gray-800 rounded-lg shadow-lg overflow-hidden transform hover:scale-105 transition duration-300">
                        <div class="h-64">
                            <img src="../../../../public/assets/images/image1Traditionnel.png" alt="Plan Traditionnel" class="w-full h-full object-cover">
                        </div>
                        <div class="p-4">
                            <h3 class="text-xl font-bold">Plan Traditionnel</h3>
                            <p class="mt-2">Un style classique et intemporel.</p>
                        </div>
                    </div>
                    <div class="bg-white text-gray-800 rounded-lg shadow-lg overflow-hidden transform hover:scale-105 transition duration-300">
                        <div class="h-64">
                            <img src="../../../../public/assets/images/image1Compact.png" alt="Plan Compact" class="w-full h-full object-cover">
                        </div>
                        <div class="p-4">
                            <h3 class="text-xl font-bold">Plan Compact</h3>
                            <p class="mt-2">Idéal pour les petits espaces.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section>
                <h2 class="text-3xl font-semibold text-center mb-6">Nos Cours de Formation</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white text-gray-800 rounded-lg shadow-lg overflow-hidden transform hover:scale-105 transition duration-300">
                        <div class="h-64">
                            <img src="../../../../public/assets/images/image1Achitecture.png " alt="Formation en Architecture" class="w-full h-full object-cover">
                        </div>
                        <div class="p-4">
                            <h3 class="text-xl font-bold">Formation en Architecture</h3>
                            <p class="mt-2">Apprenez les bases de l'architecture moderne.</p>
                        </div>
                    </div>
                    <div class="bg-white text-gray-800 rounded-lg shadow-lg overflow-hidden transform hover:scale-105 transition duration-300">
                        <div class="h-64">
                            <img src="../../../../public/assets/images/image1Design.png" alt="Formation en Design" class="w-full h-full object-cover">
                        </div>
                        <div class="p-4">
                            <h3 class="text-xl font-bold">Formation en Design</h3>
                            <p class="mt-2">Développez vos compétences en design intérieur.</p>
                        </div>
                    </div>
                    <div class="bg-white text-gray-800 rounded-lg shadow-lg overflow-hidden transform hover:scale-105 transition duration-300">
                        <div class="h-64">
                            <img src="../../../../public/assets/images/image1Construction.png" alt="Formation en Construction" class="w-full h-full object-cover">
                        </div>
                        <div class="p-4">
                            <h3 class="text-xl font-bold">Formation en Construction</h3>
                            <p class="mt-2">Maîtrisez les techniques de construction modernes.</p>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</template>
