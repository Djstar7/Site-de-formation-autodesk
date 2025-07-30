const useDelete = async (url) => {
    const token = localStorage.getItem('token');

    try {
        const response = await fetch(url, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
        });
        const data = await response.json();

        if (response.ok) {
            // if (typeof closeModalDelete === 'function') closeModalDelete();
            return { message: 'Suppression réussie' };
        } else {
            console.error(data);
            alert('Échec de l\'annulation : ' + (data.message || 'Erreur inconnue'));
            return { message: data.message || 'Erreur inconnue' };
        }
    } catch (err) {
        console.error(err);
        alert('Une erreur est survenue lors de l\'annulation');
        return { message: 'Erreur de connexion ou interne' };
    }
};

export default useDelete;
