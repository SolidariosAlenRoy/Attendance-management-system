const sidebarToggle = document.getElementById('sidebar-toggle');
            sidebarToggle.addEventListener('click', () => {
                const sidebar = document.querySelector("nav");
                const dashboard = document.querySelector(".dashboard");

                sidebar.classList.toggle("close");
                dashboard.classList.toggle("full-width");

                localStorage.setItem("status", sidebar.classList.contains("close") ? "close" : "open");
            });

            // Restore sidebar state
            if (localStorage.getItem("status") === "close") {
                $("nav").addClass("close");
                $(".dashboard").addClass("full-width");
            }

            
            