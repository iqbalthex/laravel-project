<script src="{{ asset('./assets/js/bootstrap@5.3.1.bundle.min.js') }}"></script>
<script>

const $ = q => document.querySelector(q)

@if ($alert = session('alert'))
  alert("{{ $alert['text'] }}");
@endif

</script>

@stack('scripts')

</body>
</html>
