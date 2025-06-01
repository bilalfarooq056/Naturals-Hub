const personId = window.personId;
const role = window.role;


document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('edit-form');

    fetch(`handlers/get_profile.php?person_id=${personId}&role=${role}`)
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                alert('Failed to load profile.');
                return;
            }

            const profile = data.profile;

            // Non-editable fields
            form.email.value = profile.email;
            form.email.readOnly = true;
            form.email.classList.add('readonly');

            // Editable fields
            form.full_name.value = profile.full_name;
            form.contact_number.value = profile.contact_number;
            form.address.value = profile.address;

            const roleFieldsContainer = document.getElementById('role-fields');
            roleFieldsContainer.innerHTML = '';

            if (role === 'farmer') {
                addInput(roleFieldsContainer, 'farm_name', 'Farm Name', profile.farm_name);
                addInput(roleFieldsContainer, 'farm_size', 'Farm Size', profile.farm_size);
            } else if (role === 'vendor') {
                addInput(roleFieldsContainer, 'business_type', 'Business Type', profile.business_type);
                addInput(roleFieldsContainer, 'business_name', 'Business Name', profile.business_name);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while loading your profile.');
        });
});

function addInput(container, name, label, value = '') {
    const group = document.createElement('div');
    group.className = 'form-group';
    group.innerHTML = `
        <label>${label}</label>
        <input type="text" name="${name}" value="${value || ''}" required>
    `;
    container.appendChild(group);
}


