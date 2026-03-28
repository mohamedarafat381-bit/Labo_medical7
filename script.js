// Fonction de recherche dynamique pour les patients
function searchPatients() {
    const searchTerm = document.getElementById('search').value.toLowerCase();
    const rows = document.querySelectorAll('#patients-table tbody tr');

    rows.forEach(row => {
        const name = row.cells[1].textContent.toLowerCase() + ' ' + row.cells[2].textContent.toLowerCase();
        const email = row.cells[3].textContent.toLowerCase();

        if (name.includes(searchTerm) || email.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Fonction pour ouvrir la modale
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.style.display = 'block';

    // Centrer la modale et s'assurer qu'elle est visible
    setTimeout(() => {
        const modalContent = modal.querySelector('.modal-content');
        if (modalContent) {
            // Calculer le centrage vertical
            const windowHeight = window.innerHeight;
            const modalHeight = modalContent.offsetHeight;
            const topPosition = Math.max(20, (windowHeight - modalHeight) / 2);

            modalContent.style.marginTop = topPosition + 'px';

            // Focus sur le premier champ pour l'accessibilité
            const firstInput = modalContent.querySelector('input:not([type="hidden"]), textarea, select');
            if (firstInput) {
                firstInput.focus();
            }
        }
    }, 100);
}

// Fonction pour fermer la modale
function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Fermer la modale en cliquant en dehors
window.onclick = function(event) {
    const modals = document.getElementsByClassName('modal');
    for (let modal of modals) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
}

// Validation du formulaire d'ajout de patient
function validatePatientForm() {
    const nom = document.getElementById('nom').value.trim();
    const prenom = document.getElementById('prenom').value.trim();
    const email = document.getElementById('email').value.trim();
    const telephone = document.getElementById('telephone').value.trim();

    if (!nom || !prenom || !email || !telephone) {
        alert('Tous les champs sont obligatoires.');
        return false;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert('Veuillez entrer un email valide.');
        return false;
    }

    return true;
}

// Validation du formulaire d'ajout de prélèvement
function validatePrelevementForm() {
    const typeAnalyse = document.getElementById('type_analyse').value.trim();
    const resultat = document.getElementById('resultat').value.trim();
    const datePrelevement = document.getElementById('date_prelevement').value;

    if (!typeAnalyse || !resultat || !datePrelevement) {
        alert('Tous les champs sont obligatoires.');
        return false;
    }

    return true;
}

// Initialisation des événements
document.addEventListener('DOMContentLoaded', function() {
    // Recherche dynamique
    const searchInput = document.getElementById('search');
    if (searchInput) {
        searchInput.addEventListener('input', searchPatients);
    }

    // Fermeture des modales
    const closeButtons = document.getElementsByClassName('close');
    for (let closeBtn of closeButtons) {
        closeBtn.onclick = function() {
            const modal = this.closest('.modal');
            modal.style.display = 'none';
        }
    }

    // Recalculer le centrage des modales à la redimension de la fenêtre
    window.addEventListener('resize', function() {
        const openModals = document.querySelectorAll('.modal[style*="display: block"]');
        openModals.forEach(modal => {
            const modalContent = modal.querySelector('.modal-content');
            if (modalContent) {
                const windowHeight = window.innerHeight;
                const modalHeight = modalContent.offsetHeight;
                const topPosition = Math.max(20, (windowHeight - modalHeight) / 2);
                modalContent.style.marginTop = topPosition + 'px';
            }
        });
    });
});