<script src="{{ asset('./assets/js/bootstrap@5.3.1.bundle.min.js') }}"></script>
<script>

const $ = q => document.querySelector(q);
const $data = q => document.querySelector(`[data-${q}]`);

const enable  = e => e.removeAttribute('disabled');
const disable = e => e.setAttribute('disabled', true);

const show = e => e.classList.remove('d-none');
const hide = e => e.classList.add('d-none');

@if ($alert = session('alert'))
  alert("{{ $alert['text'] }}");
@endif

</script>

@stack('scripts')

</body>
</html>
