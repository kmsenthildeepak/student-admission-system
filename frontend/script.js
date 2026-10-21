// Client-side validation improves usability; server-side validation is still required.
const form = document.getElementById('registrationForm');
const setError = (id, message) => document.getElementById(id).textContent = message;
form.addEventListener('submit', function (event) {
  event.preventDefault();
  ['nameError','rollError','emailError','courseError','passwordError','fileError'].forEach(id=>setError(id,''));
  document.getElementById('success').textContent = '';
  let valid = true;
  const name = document.getElementById('name').value.trim();
  const roll = document.getElementById('roll').value.trim();
  const email = document.getElementById('email').value.trim();
  const course = document.getElementById('course').value;
  const password = document.getElementById('password').value;
  const file = document.getElementById('document').files[0];
  if (!name) { setError('nameError','Name is required.'); valid=false; }
  if (!roll) { setError('rollError','Roll number is required.'); valid=false; }
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { setError('emailError','Enter a valid email address.'); valid=false; }
  if (!course) { setError('courseError','Select a course.'); valid=false; }
  if (!/(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}/.test(password)) { setError('passwordError','Password must be 8+ characters with uppercase, lowercase and a number.'); valid=false; }
  if (file) {
    const allowed = ['image/jpeg','image/png','application/pdf'];
    if (!allowed.includes(file.type)) { setError('fileError','Only JPG, PNG or PDF files are allowed.'); valid=false; }
    if (file.size > 2*1024*1024) { setError('fileError','File must be 2 MB or smaller.'); valid=false; }
  }
  if (valid) document.getElementById('success').textContent = 'Validation successful. Connect this form to the PHP backend to save the record.';
});
