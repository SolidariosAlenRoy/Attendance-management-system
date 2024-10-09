const flipCardInner = document.querySelector('.flip-card-inner');
const usernameInput = document.getElementById('username');
const passwordInput = document.getElementById('password');
const departmentSelect = document.getElementById('dept'); 

const flipCard = () => {
    flipCardInner.style.transform = flipCardInner.style.transform === 'rotateY(180deg)' ? 'rotateY(0deg)' : 'rotateY(180deg)';
};

flipCardInner.addEventListener('click', flipCard);

const preventFlipping = () => {
    flipCardInner.style.pointerEvents = 'none'; 
};

usernameInput.addEventListener('focus', preventFlipping);
passwordInput.addEventListener('focus', preventFlipping);
departmentSelect.addEventListener('focus', preventFlipping); 

const enableFlipping = () => {
    flipCardInner.style.pointerEvents = 'auto'; 
};

usernameInput.addEventListener('blur', enableFlipping);
passwordInput.addEventListener('blur', enableFlipping);
departmentSelect.addEventListener('blur', enableFlipping); 
