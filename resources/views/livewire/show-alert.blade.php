<div>
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('swal:modal', (event) => {
                Swal.fire({
                    icon: event[0].type,
                    title: event[0].title,
                    text: event[0].text,
                    timer: 3000,
                    timerProgressBar: true,
                });
            });
        });
    </script>
</div>