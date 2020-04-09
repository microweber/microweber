$(window).on("load", function() {
    var e = "#5A8DEE",
        a = "#FF5B5C",
        t = "#FDAC41",
        r = "#00CFDD",
        o = "#828D99",
        s = {
            chart: {
                height: 240,
                type: "radialBar"
            },
            colors: [e, a, t],
            series: [75, 80, 85],
            plotOptions: {
                radialBar: {
                    offsetY: -10,
                    hollow: {
                        size: "40%"
                    },
                    track: {
                        margin: 10,
                        background: "#fff"
                    },
                    dataLabels: {
                        name: {
                            fontSize: "15px",
                            color: [o],
                            fontFamily: "IBM Plex Sans",
                            offsetY: 25
                        },
                        value: {
                            fontSize: "30px",
                            fontFamily: "Rubik",
                            offsetY: -15
                        },
                        total: {
                            show: !0,
                            label: "Total Visits",
                            color: o
                        }
                    }
                }
            },
            stroke: {
                lineCap: "round"
            },
            labels: ["Target", "Mart", "Ebay"]
        };
    new ApexCharts(document.querySelector("#multi-radial-chart"), s).render();
    var i = {
        chart: {
            height: 100,
            type: "bar",
            stacked: !0,
            toolbar: {
                show: !1
            }
        },
        grid: {
            show: !1,
            padding: {
                left: 0,
                right: 0,
                top: -20,
                bottom: -15
            }
        },
        plotOptions: {
            bar: {
                horizontal: !1,
                columnWidth: "20%",
                endingShape: "rounded"
            }
        },
        legend: {
            show: !1
        },
        dataLabels: {
            enabled: !1
        },
        colors: [r, "#e7edf3"],
        series: [{
            name: "2019",
            data: [50, 70, 100, 120, 140, 100, 70, 80, 90, 110, 50, 70, 35, 110, 100, 105, 125, 80]
        }, {
            name: "2018",
            data: [70, 50, 20, 30, 20, 90, 90, 60, 50, 0, 50, 60, 140, 50, 20, 20, 10, 0]
        }],
        xaxis: {
            categories: ["0", "", "", "", "", "10", "", "", "", "", "", "15", "", "", "", "", "", "20"],
            axisBorder: {
                show: !1
            },
            axisTicks: {
                show: !1
            },
            labels: {
                style: {
                    colors: o
                },
                offsetY: -5
            }
        },
        yaxis: {
            show: !1,
            floating: !0
        },
        tooltip: {
            x: {
                show: !1
            }
        }
    };
    new ApexCharts(document.querySelector("#revenue-growth-chart"), i).render();
    var l = {
        chart: {
            height: 270,
            type: "line",
            stacked: !1,
            toolbar: {
                show: !1
            },
            sparkline: {
                enabled: !0
            }
        },
        colors: [e, e],
        dataLabels: {
            enabled: !1
        },
        stroke: {
            curve: "smooth",
            width: 2.5,
            dashArray: [0, 8]
        },
        fill: {
            type: "gradient",
            gradient: {
                inverseColors: !1,
                shade: "light",
                type: "vertical",
                gradientToColors: ["#E2ECFF", e],
                opacityFrom: .7,
                opacityTo: .55,
                stops: [0, 80, 100]
            }
        },
        series: [{
            name: "Weeks",
            data: [165, 175, 162, 173, 160, 195, 160, 170, 160, 190, 180],
            type: "area"
        }, {
            name: "Months",
            data: [168, 168, 155, 178, 155, 170, 190, 160, 150, 170, 140],
            type: "line"
        }],
        xaxis: {
            offsetY: -50,
            categories: ["", 1, 2, 3, 4, 5, 6, 7, 8, 9, ""],
            axisBorder: {
                show: !1
            },
            axisTicks: {
                show: !1
            },
            labels: {
                show: !0,
                style: {
                    colors: o
                }
            }
        },
        tooltip: {
            x: {
                show: !1
            }
        }
    };
    new ApexCharts(document.querySelector("#order-summary-chart"), l).render();
    var n = {
        chart: {
            width: 80,
            height: 110,
            type: "donut"
        },
        dataLabels: {
            enabled: false
        },
        series: [70, 30, 40],
        labels: [
            "Installation",
            "Page Views",
            "Active Users"
        ],
        stroke: {
            width: 2
        },
        colors: [t, r, e],
        plotOptions: {
            pie: {
                offsetY: 15,
                donut: {
                    size: "70%"
                }
            }
        },
        legend: {
            show: !1
        }
    };
    new ApexCharts(document.querySelector("#donut-success-chart"), n).render();
    var d = {
        chart: {
            width: 80,
            height: 110,
            type: "donut"
        },
        dataLabels: {
            enabled: !1
        },
        series: [70, 40, 30],
        labels: ["Installation", "Page Views", "Active Users"],
        stroke: {
            width: 2
        },
        colors: [a, o, e],
        plotOptions: {
            pie: {
                offsetY: 15,
                donut: {
                    size: "70%"
                }
            }
        },
        legend: {
            show: !1
        }
    };
    if (new ApexCharts(document.querySelector("#donut-danger-chart"), d).render(), 0 < $(".widget-earnings-scroll").length) new PerfectScrollbar(".widget-earnings-scroll");
    if (0 < $(".dashboard-latest-update .card-body").length) new PerfectScrollbar(".dashboard-latest-update .card-body");
    if (0 < $(".marketing-campaigns .table-responsive").length) new PerfectScrollbar(".marketing-campaigns .table-responsive");
    var c = $(".swiper-slide").length;
    c && (c = Math.floor(c / 2));
    var h = new Swiper(".widget-earnings-swiper", {
        slidesPerView: "auto",
        initialSlide: c,
        centeredSlides: !0,
        spaceBetween: 30,
        slideToClickedSlide: !0
    });

    function p(e) {
        var a = h.slides[e],
            t = $(a).attr("id");
        $(".wrapper-content").removeClass("active"), $("[data-earnings=" + t + "]").addClass("active")
    }
    p(c), h.on("slideChange", function() {
        p(h.realIndex)
    })
});
