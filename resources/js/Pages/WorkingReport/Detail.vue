<script setup>
import { onMounted, onUnmounted, nextTick, ref, computed, toRef, watch } from 'vue'
import { useForm, Link, usePage } from '@inertiajs/inertia-vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Card from '@/Components/Card.vue'
import Select from '@vueform/multiselect'
import Button from '@/Components/Button.vue'
import ButtonGreen from '@/Components/Button/Green.vue'
import ButtonBlue from '@/Components/Button/Blue.vue'
import Input from '@/Components/Input.vue'
import InputError from '@/Components/InputError.vue'
import TextArea from '@/Components/TextArea.vue'
import Swal from 'sweetalert2'
import Modal from '@/Components/Modal.vue'
import Close from '@/Components/Button/Close.vue'
import Icon from '@/Components/Icon.vue'
import axios from 'axios';
import BtnAttachment from '@/Components/Button/Attachment.vue'
import AttachmentInline from '@/Components/Button/AttachmentInline.vue'

import { result } from 'lodash'
// import moment from 'moment'; 

const props = defineProps({
    report: Object,
    checksheet: Array,
    checksheetday: Array,
    checksheetworkresult: Array,
    warmingup: Array,
    workresult: Array,
    machines: Array,
    regions: Array,
    users: Array,
    warmingup_user: Array,
    workresult_user: Array,
    masters: Array,
    results: Array,
    upload: Array,
    mglurusanawal: Object,
    mglengkunganawal: Object,
    mgweselawal: Object,
    pemeriksaansilangkpjr: Object,
    pemeriksaansilanglahan: Object,
    perekamanawal: Object,
    mglurusanawal_attachments: Array,
    mglengkunganawal_attachments: Array,
    mgweselawal_attachments: Array,
    pemeriksaansilangkpjr_attachments: Array,
    pemeriksaansilanglahan_attachments: Array,
    perekamanawal_attachments: Array,
    mglurusanakhir: Object,
    mglengkunganakhir: Object,
    mgweselakhir: Object,
    perekamanakhir: Object,
    mglurusanakhir_attachments: Array,
    mglengkunganakhir_attachments: Array,
    mgweselakhir_attachments: Array,
    perekamanakhir_attachments: Array,
})

const { user } = usePage().props.value

const form = useForm({
    machine_id: props.report?.machine_id || '',
    region_id: props.report?.region_id || '',
    date: props.report?.date || '',
    status: props.report?.status || '',
    cuaca: props.report?.cuaca || '',
    jenis_kpjr: props.report?.jenis_kpjr || '',
    nomor_mesin: props.report?.nomor_mesin || '',
    nomor_sarana: props.report?.nomor_sarana || '',
    waktu_start_engine: props.report?.waktu_start_engine?.slice(0, 5) || '',
    jam_traveling_awal: props.report?.jam_traveling_awal || '',
    jam_kerja_awal: props.report?.jam_kerja_awal || '',
    jam_mesin_awal: props.report?.jam_mesin_awal || '',
    jam_generator_awal: props.report?.jam_generator_awal || '',
    counter_tamping_awal: props.report?.counter_tamping_awal || '',
    oddometer_awal: props.report?.oddometer_awal || '',
    hsd_awal_kerja: props.report?.hsd_awal_kerja || '',
    operator_by1: props.report?.operator_by1 || '',
    operator_by2: props.report?.operator_by2 || '',
    operator_by3: props.report?.operator_by3 || '',
    approved_by: props.report?.approved_by || '',
    approved_by1: props.report?.approved_by1 || '',
    note: props.report?.note || '',
    mode: props.report?.mode || '',
});

const form1 = useForm({
    id: props.checksheetday?.id || null,
    working_report_id: props.report?.id || null,
    no_seri: props.checksheetday?.no_seri || null,
    jenis: props.checksheetday?.jenis || null,
    jam_mesin: props.checksheetday?.jam_mesin?.slice(0, 5) || null,
    counter_pecok: props.checksheetday?.counter_pecok || null,
    // kilometer_mesin: props.checksheetday?.kilometer_mesin || null,
    kilometer_mesin: props.checksheetday?.kilometer_mesin ? parseFloat(props.checksheetday.kilometer_mesin) : null,
    tanggal: props.checksheetday?.tanggal || null,
    lokasi: props.checksheetday?.lokasi || null,
    wilayah: props.checksheetday?.wilayah || null,
    region_id: props.checksheetday?.region_id || null,
    note: props.checksheetday?.note || null,
    results: props.results ?? [],
});

const form2 = useForm({
  id: props.checksheetworkresult?.id ?? null,
  working_report_id: props.report?.id || null,
  check_sheet_day_id: props.checksheetday?.id ?? null,
  catatan_gangguan: props.checksheetworkresult?.catatan_gangguan ?? '',
  lokasi_dan_jam1: props.checksheetworkresult?.lokasi_dan_jam1 ?? '',
  hu_hi_1: props.checksheetworkresult?.hu_hi_1 ?? '',
  jumlah_1: props.checksheetworkresult?.jumlah_1 ?? '',
  lokasi_dan_jam2: props.checksheetworkresult?.lokasi_dan_jam2 ?? '',
  hu_hi_2: props.checksheetworkresult?.hu_hi_2 ?? '',
  jumlah_2: props.checksheetworkresult?.jumlah_2 ?? '',
  lokasi_dan_jam3: props.checksheetworkresult?.lokasi_dan_jam3 ?? '',
  hu_hi_3: props.checksheetworkresult?.hu_hi_3 ?? '',
  jumlah_3: props.checksheetworkresult?.jumlah_3 ?? '',
  operator_by1: props.checksheetworkresult?.operator_by1 ?? '',
  operator_by2: props.checksheetworkresult?.operator_by2 ?? '',
  operator_by3: props.checksheetworkresult?.operator_by3 ?? '',
  operator_by4: props.checksheetworkresult?.operator_by4 ?? '',
  operator_at1: props.checksheetworkresult?.operator_at1 ?? '',
  operator_at2: props.checksheetworkresult?.operator_at2 ?? '',
  operator_at3: props.checksheetworkresult?.operator_at3 ?? '',
  operator_at4: props.checksheetworkresult?.operator_at4 ?? '',
  validasi1: props.checksheetworkresult?.validasi1 ?? '',
  validasi2: props.checksheetworkresult?.validasi2 ?? '',
  validasi3: props.checksheetworkresult?.validasi3 ?? '',
  validasi4: props.checksheetworkresult?.validasi4 ?? '',
  mode: props.checksheetworkresult?.mode ?? '',
});

// onMounted(() => {
//   console.log("Loaded Results:", props.checksheetworkresult);
// });

const form3 = useForm({
    id: props.warmingup?.id || null,
    working_report_id: props.report?.id || null,
    tanggal: props.warmingup?.tanggal || '',
    cuaca: props.warmingup?.cuaca || '',
    jenis_kpjr: props.warmingup?.jenis_kpjr || '',
    nomor_mesin: props.warmingup?.nomor_mesin || '',
    nomor_sarana: props.warmingup?.nomor_sarana || '',
    waktu_start_engine: props.warmingup?.waktu_start_engine?.slice(0, 5) || '',
    jam_traveling_awal: props.warmingup?.jam_traveling_awal || '',
    jam_kerja_awal: props.warmingup?.jam_kerja_awal || '',
    jam_mesin_awal: props.warmingup?.jam_mesin_awal || '',
    jam_generator_awal: props.warmingup?.jam_generator_awal || '',
    counter_tamping_awal: props.warmingup?.counter_tamping_awal || '',
    oddometer_awal: props.warmingup?.oddometer_awal || '',
    hsd_awal_kerja: props.warmingup?.hsd_awal_kerja || '',
    waktu_stop_engine: props.warmingup?.waktu_stop_engine?.slice(0, 5) || '',
    jam_traveling_akhir: props.warmingup?.jam_traveling_akhir || '',
    jam_kerja_akhir: props.warmingup?.jam_kerja_akhir || '',
    jam_mesin_akhir: props.warmingup?.jam_mesin_akhir || '',
    jam_generator_akhir: props.warmingup?.jam_generator_akhir || '',
    counter_tamping_akhir: props.warmingup?.counter_tamping_akhir || '',
    oddometer_akhir: props.warmingup?.oddometer_akhir || '',
    hsd_akhir_kerja: props.warmingup?.hsd_akhir_kerja || '',
    konsumsi_hsd: props.warmingup?.konsumsi_hsd || '',
    operator_by1: props.warmingup?.operator_by1 || '',
    operator_by2: props.warmingup?.operator_by2 || '',
    operator_by3: props.warmingup?.operator_by3 || '',
    approved_by: props.warmingup?.approved_by || '',
    approved_by1: props.warmingup?.approved_by1 || '',
    note: props.warmingup?.note || null,
    user_id: props.warmingup?.warmingup_user.map(warmingup_user => warmingup_user.user_id) || [],
});

const form4 = useForm({
    id: props.workresult?.id || null,
    working_report_id: props.report?.id || null,
    wilayah: props.workresult?.wilayah || null,
    petak_jalan: props.workresult?.petak_jalan || null,
    kelas_jalan: props.workresult?.kelas_jalan || null,
    lokasi_stabling_awal: props.workresult?.lokasi_stabling_awal || null,
    lokasi_stabling_akhir: props.workresult?.lokasi_stabling_akhir || null,
    lokasi_awal1: props.workresult?.lokasi_awal1 || null,
    lokasi_akhir1: props.workresult?.lokasi_akhir1 || null,
    jumlah1: props.workresult?.jumlah1 || null,
    lokasi_awal2: props.workresult?.lokasi_awal2 || null,
    lokasi_akhir2: props.workresult?.lokasi_akhir2 || null,
    jumlah2: props.workresult?.jumlah2 || null,
    lokasi_awal3: props.workresult?.lokasi_awal3 || null,
    lokasi_akhir3: props.workresult?.lokasi_akhir3 || null,
    jumlah3: props.workresult?.jumlah3 || null,
    total_distance: props.workresult?.total_distance || null,
    no_wesel1: props.workresult?.no_wesel1 || null,
    km_hm1: props.workresult?.km_hm1 || null,
    jumlah_wesel1: props.workresult?.jumlah_wesel1 || null,
    no_wesel2: props.workresult?.no_wesel2 || null,
    km_hm2: props.workresult?.km_hm2 || null,
    jumlah_wesel2: props.workresult?.jumlah_wesel2 || null,
    no_wesel3: props.workresult?.no_wesel3 || null,
    km_hm3: props.workresult?.km_hm3 || null,
    jumlah_wesel3: props.workresult?.jumlah_wesel3 || null,
    total_wesel: props.workresult?.total_wesel || null,
    waktu_stop_engine: props.workresult?.waktu_stop_engine?.slice(0, 5) || null,
    jam_traveling_akhir: props.workresult?.jam_traveling_akhir || null,
    jam_kerja_akhir: props.workresult?.jam_kerja_akhir?.slice(0, 5) || null,
    jam_mesin_akhir: props.workresult?.jam_mesin_akhir || null,
    jam_generator_akhir: props.workresult?.jam_generator_akhir || null,
    counter_tamping_akhir: props.workresult?.counter_tamping_akhir || null,
    oddometer_akhir: props.workresult?.oddometer_akhir || null,
    hsd_akhir_kerja: props.workresult?.hsd_akhir_kerja || null,
    konsumsi_hsd: props.workresult?.konsumsi_hsd || null,
    hu_hi1: props.workresult?.hu_hi1 || null,
    hu_hi2: props.workresult?.hu_hi2 || null,
    hu_hi3: props.workresult?.hu_hi3 || null,
    hu_hi4: props.workresult?.hu_hi4 || null,
    hu_hi5: props.workresult?.hu_hi5 || null,
    hu_hi6: props.workresult?.hu_hi6 || null,
    operator_by1: props.workresult?.operator_by1 || null,
    operator_at1: props.workresult?.operator_at1 || null,
    operator_by2: props.workresult?.operator_by2 || null,
    operator_at2: props.workresult?.operator_at2 || null,
    operator_by3: props.workresult?.operator_by3 || null,
    operator_at3: props.workresult?.operator_at3 || null,
    note: props.workresult?.note || null,
});

const form5 = useForm({
    id: props.upload?.id || null,
    working_report_id: props.report?.id || null,
    date: props.upload?.date || null,
});

const form6 = useForm({
  machine_id: props.report?.machine_id || '',
  region_id: props.report?.region_id || '',
  date: props.report?.date || '',
  has_trouble: props.report?.has_trouble || '',
  status: props.report?.status || '',
  cuaca: props.report?.cuaca || '',
  jenis_kpjr: props.report?.jenis_kpjr || '',
  nomor_mesin: props.report?.nomor_mesin || '',
  nomor_sarana: props.report?.nomor_sarana || '',
  waktu_start_engine: props.report?.waktu_start_engine || '',
  jam_traveling_awal: props.report?.jam_traveling_awal || '',
  jam_kerja_awal: props.report?.jam_kerja_awal || '',
  jam_mesin_awal: props.report?.jam_mesin_awal || '',
  jam_generator_awal: props.report?.jam_generator_awal || '',
  counter_tamping_awal: props.report?.counter_tamping_awal || '',
  oddometer_awal: props.report?.oddometer_awal || '',
  hsd_awal_kerja: props.report?.hsd_awal_kerja || '',
  operator_by1: props.report?.operator_by1 || '',
  operator_by2: props.report?.operator_by2 || '',
  operator_by3: props.report?.operator_by3 || '',
  approved_by: props.report?.approved_by || '',
  approved_by1: props.report?.approved_by1 || '',
  note: props.report?.note || '',
})

const form7 = useForm({
  id: props.mglurusanawal?.id || null,
  working_report_id: props.report?.id || null,
  ada: props.mglurusanawal?.ada || '0', 
  tidak: props.mglurusanawal?.tidak || '0',
})

const form8 = useForm({
  id: props.mglengkunganawal?.id || null,
  working_report_id: props.report?.id || null,
  ada: props.mglengkunganawal?.ada || '0', 
  tidak: props.mglengkunganawal?.tidak || '0',
})

const form9 = useForm({
  id: props.mgweselawal?.id || null,
  working_report_id: props.report?.id || null,
  ada: props.mgweselawal?.ada || '0', 
  tidak: props.mgweselawal?.tidak || '0',
})

const form10 = useForm({
  id: props.pemeriksaansilangkpjr?.id || null,
  working_report_id: props.report?.id || null,
  ada: props.pemeriksaansilangkpjr?.ada || '0', 
  tidak: props.pemeriksaansilangkpjr?.tidak || '0',
})

const form11 = useForm({
  id: props.pemeriksaansilanglahan?.id || null,
  working_report_id: props.report?.id || null,
  ada: props.pemeriksaansilanglahan?.ada || '0', 
  tidak: props.pemeriksaansilanglahan?.tidak || '0',
})

const form12 = useForm({
  id: props.perekamanawal?.id || null,
  working_report_id: props.report?.id || null,
  ada: props.perekamanawal?.ada || '0', 
  tidak: props.perekamanawal?.tidak || '0',
})

const form13 = useForm({
  id: props.mglurusanakhir?.id || null,
  working_report_id: props.report?.id || null,
  ada: props.mglurusanakhir?.ada || '0', 
  tidak: props.mglurusanakhir?.tidak || '0',
})

const form14 = useForm({
  id: props.mglengkunganakhir?.id || null,
  working_report_id: props.report?.id || null,
  ada: props.mglengkunganakhir?.ada || '0', 
  tidak: props.mglengkunganakhir?.tidak || '0',
})

const form15 = useForm({
  id: props.mgweselakhir?.id || null,
  working_report_id: props.report?.id || null,
  ada: props.mgweselakhir?.ada || '0', 
  tidak: props.mgweselakhir?.tidak || '0',
})

const form16 = useForm({
  id: props.perekamanakhir?.id || null,
  working_report_id: props.report?.id || null,
  ada: props.perekamanakhir?.ada || '0', 
  tidak: props.perekamanakhir?.tidak || '0',
})

watch(() => props.mglurusanawal_attachments, (val) => {
    if (val?.length > 0) {
        form7.ada = "1";
        form7.tidak = "0";
    }
}, { immediate: true });

watch(() => props.mglengkunganawal_attachments, (val) => {
    if (val?.length > 0) {
        form8.ada = "1";
        form8.tidak = "0";
    }
}, { immediate: true });

watch(() => props.mgweselawal_attachments, (val) => {
    if (val?.length > 0) {
        form9.ada = "1";
        form9.tidak = "0";
    }
}, { immediate: true });

watch(() => props.pemeriksaansilangkpjr_attachments, (val) => {
    if (val?.length > 0) {
        form10.ada = "1";
        form10.tidak = "0";
    }
}, { immediate: true });

watch(() => props.pemeriksaansilanglahan_attachments, (val) => {
    if (val?.length > 0) {
        form11.ada = "1";
        form11.tidak = "0";
    }
}, { immediate: true });

watch(() => props.perekamanawal_attachments, (val) => {
    if (val?.length > 0) {
        form12.ada = "1";
        form12.tidak = "0";
    }
}, { immediate: true });

watch(() => props.mglurusanakhir_attachments, (val) => {
    if (val?.length > 0) {
        form13.ada = "1";
        form13.tidak = "0";
    }
}, { immediate: true });

watch(() => props.mglengkunganakhir_attachments, (val) => {
    if (val?.length > 0) {
        form14.ada = "1";
        form14.tidak = "0";
    }
}, { immediate: true });

watch(() => props.mgweselakhir_attachments, (val) => {
    if (val?.length > 0) {
        form15.ada = "1";
        form15.tidak = "0";
    }
}, { immediate: true });

watch(() => props.perekamanakhir_attachments, (val) => {
    if (val?.length > 0) {
        form16.ada = "1";
        form16.tidak = "0";
    }
}, { immediate: true });

const render = ref(true)
const table = ref(null)
const open = ref(false)
const show = () => open.value = true
const localReport = ref(props.report);
const localChecksheetDay = ref(props.checksheetday);

const close = () => {
  form4.reset()
  render.value = false
  nextTick(() => {
    nextTick(() => open.value = false)
    render.value = true
  })
}

const currentStep = ref(1)
const currentStep1 = ref(1)
const showNextButton = ref(false)
const currentGroupIndex = ref(0)

const groups = computed(() => {
  const uniqueGroups = [...new Set(props.results.map(r => r.group_name))]
  return uniqueGroups
})

const currentGroupResults = computed(() => {
  return props.results.filter(r => r.group_name === groups.value[currentGroupIndex.value])
})

const nextGroup = () => {
  if (currentGroupIndex.value === groups.value.length - 1) {
    currentStep.value = 3 
  } else if (currentGroupIndex.value < groups.value.length - 1) {
    currentGroupIndex.value++
  }
}

const goToStep1 = () => {
  if (currentStep.value === 2) {
    currentStep.value = 1
  }
}

const prevGroup = () => {
  if (currentGroupIndex.value > 0) {
    currentGroupIndex.value-- 
  } 
  else if (currentStep.value === 2) {
    goToStep1()
  }
}

const approve = (level) => {
    Swal.fire({
        title: 'Memproses...',
        didOpen: () => Swal.showLoading(),
        allowOutsideClick: false,
    });

    axios.post(route('working-reports.approve', {
        report: props.report.id,
        level: level
    }))
    .then(() => {
        const now = new Date().toISOString();

        if (level === 1) {
            localReport.value.operator_at1 = now; 
        } else if (level === 2) {
            localReport.value.operator_at2 = now;
        } else if (level === 3) {
            localReport.value.operator_at3 = now;
        }

        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: `Approve Operator ${level} berhasil.`,
            timer: 1200,
            showConfirmButton: false,
        });
    })
    .catch(() => {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Terjadi kesalahan saat approve.',
        });
    });
}

const approvePengawal = (level) => {
    Swal.fire({
        title: 'Memproses...',
        didOpen: () => Swal.showLoading(),
        allowOutsideClick: false,
    });

    axios.post(route('working-reports.approvePengawal', {
        report: props.report.id,
        level: level
    }))
    .then(() => {
        const now = new Date().toISOString();

        if (level === 1) {
            localReport.value.approved_at = now; 
        } else if (level === 2) {
            localReport.value.approved_at1 = now;
        } 

        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: `Approve Pengawal ${level} berhasil.`,
            timer: 1200,
            showConfirmButton: false,
        });
    })
    .catch(() => {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Terjadi kesalahan saat approve.',
        });
    });
}

const approveKUPT = async () => {
  const report = localReport.value 
  if (!report) return

  const confirm = await Swal.fire({
    title: "Apakah Anda yakin?",
    text: "Anda akan menyetujui laporan ini.",
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Ya, setujui",
    cancelButtonText: "Batal",
  })

  if (!confirm.isConfirmed) return 

  try {
    const res = await axios.post(route("working-reports.approveKUPT"), {
      id: report.id,
    })

    const now = new Date().toISOString();

    if (user.id) {
        localReport.value.kupt_by1 = user.id; 
    }
    
    localReport.value.kupt_at1 = now; 

    Swal.fire({
      icon: "success",
      title: res.data.message || "Berhasil disetujui!",
      timer: 1500,
      showConfirmButton: false,
    })

  } catch (error) {
    console.error(error)
    Swal.fire({
      icon: "error",
      title: "Gagal approve!",
      text: error.response?.data?.message || error.message,
    })
  }
}

const submitchecksheetday = () => {
  Swal.fire({
    title: 'Menyimpan data...',
    didOpen: () => Swal.showLoading(),
    allowOutsideClick: false,
  })

  form1.post(route('check-sheet-day.store', props.report.id), {
    onSuccess: () => {
      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: 'Data berhasil disimpan!',
        timer: 1000,
        showConfirmButton: false,
      });
      setTimeout(() => window.location.reload(), 1000);
      showNextButton.value = true 
    },
    onError: () => {
      show();
      Swal.hideLoading();
      Swal.fire({
        title: 'Terjadi kesalahan!',
        text: 'Gagal menyimpan data.',
        icon: 'error',
      });
    },
  })
}

const updatechecksheetday = async () => {
  try {
    const response = await Swal.fire({
      title: 'Konfirmasi',
      html: `Apakah anda yakin akaan ubah data ini?`,
      icon: 'question',
      showCancelButton: true,
      showCloseButton: true,
    });

    if (response.isConfirmed) {
      Swal.fire({
        title: 'Sedang menyimpan data...',
        didOpen: () => {
          Swal.showLoading();
        },
        allowOutsideClick: false,
      });

      const result = await form1.patch(route('check-sheet-day.update', form1.id), {
        onSuccess: () => {
          close();
          Swal.showLoading();
          Swal.fire({
            title: 'Data berhasil diupdate!',
            icon: 'success',
          });
        },
        onError: () => {
          Swal.showLoading();
          Swal.fire({
            title: 'Terjadi kesalahan!',
            text: 'Gagal menyimpan data.',
            icon: 'error',
          });
        },
      });

      return result;
    }
  } catch (error) {
    console.error(error);
  }
};

const toggleResult = async (item, field) => {
  const previousValue = item[field];
  item[field] = item[field] == 1 ? 0 : 1;

  try {
    const response = await axios.post(route("checksheetday-results.autosave"), {
      check_sheet_day_id: form1.id,
      check_sheet_master_day_id: item.check_sheet_master_day_id,
      cek: item.cek ?? 0,
      tambahan: item.tambahan ?? 0,
      ganti: item.ganti ?? 0,
      kiri_depan: item.kiri_depan ?? '',
      kanan_depan: item.kanan_depan ?? '',
      keterangan: item.keterangan ?? '',
    });

    // console.log(`Autosaved ${field}: ${item[field]}`);

    Swal.fire({
      icon: "success",
      title: previousValue == null ? "Berhasil Disimpan" : "Berhasil Diperbarui",
      timer: 1000,
      showConfirmButton: false,
    });
  } catch (error) {
    console.error("Autosave failed:", error);
    Swal.fire("Error", "Gagal menyimpan data!", "error");
  }
};

const saveTextField = async (item) => {
  try {
    await axios.post(route("checksheetday-results.autosave"), {
      check_sheet_day_id: form1.id,
      check_sheet_master_day_id: item.check_sheet_master_day_id,
      cek: item.cek ?? 0,
      tambahan: item.tambahan ?? 0,
      ganti: item.ganti ?? 0,
      kiri_depan: item.kiri_depan ?? '',
      kanan_depan: item.kanan_depan ?? '',
      keterangan: item.keterangan ?? '',
    });

    Swal.fire({
      icon: "success",
      title: "Berhasil disimpan",
      timer: 1000,
      showConfirmButton: false,
    });
  } catch (error) {
    console.error("Autosave failed:", error);
    Swal.fire("Error", "Gagal menyimpan data!", "error");
  }
};

const autosaveUpload = async () => {
  try {
    await axios.post(route('upload.autosave'), {
      working_report_id: form5.working_report_id,
      date: form5.date,
    })

    Swal.fire({
      toast: true,
      position: 'bottom-end',
      icon: 'success',
      title: 'Data tersimpan otomatis',
      showConfirmButton: false,
      timer: 1000,
    }).then(() => {
      window.location.reload()
    })
  } catch (error) {
    console.error(error)
    Swal.fire({
      toast: true,
      position: 'bottom-end',
      icon: 'error',
      title: 'Gagal menyimpan data',
      showConfirmButton: false,
      timer: 1500,
    })
  }
}

// const saveWorkResult = async () => {
//   try {
//     await axios.post(route("checksheet-workresult.autosave"), {
//       id: form2.id,
//       working_report_id: form2.working_report_id,
//       check_sheet_day_id: form2.check_sheet_day_id,
//       catatan_gangguan: form2.catatan_gangguan,
//       lokasi_dan_jam1: form2.lokasi_dan_jam1,
//       hu_hi_1: form2.hu_hi_1,
//       jumlah_1: form2.jumlah_1,
//       lokasi_dan_jam2: form2.lokasi_dan_jam2,
//       hu_hi_2: form2.hu_hi_2,
//       jumlah_2: form2.jumlah_2,
//       lokasi_dan_jam3: form2.lokasi_dan_jam3,
//       hu_hi_3: form2.hu_hi_3,
//       jumlah_3: form2.jumlah_3,
//       // operator_by1: form2.operator_by1,
//       // operator_by2: form2.operator_by2,
//       // operator_by3: form2.operator_by3,
//       // operator_by4: form2.operator_by4,
//       operator_by1: form2.operator_by1?.value ?? form2.operator_by1 ?? null,
//       operator_by2: form2.operator_by2?.value ?? form2.operator_by2 ?? null,
//       operator_by3: form2.operator_by3?.value ?? form2.operator_by3 ?? null,
//       operator_by4: form2.operator_by4?.value ?? form2.operator_by4 ?? null,
//       validasi1: form2.validasi1,
//       validasi2: form2.validasi2,
//       validasi3: form2.validasi3,
//       validasi4: form2.validasi4,
//     })

//     Swal.fire({
//       icon: "success",
//       title: "Berhasil disimpan otomatis",
//       timer: 1000,
//       showConfirmButton: false,
//     })
//   } catch (error) {
//     console.error("Autosave gagal:", error)
//     Swal.fire("Error", "Gagal menyimpan data!", "error")
//   }
// }

const submitchecksheetworkresult = async () => {
  try {
    await axios.post(route("checksheet-workresult.store"), form2)
    Swal.fire({
      icon: "success",
      title: 'Berhasil!',
      text: 'Data berhasil disimpan!',
      timer: 1500,
      showConfirmButton: false,
    });
    setTimeout(() => window.location.reload(), 1000);
  } catch (error) {
    console.error(error)
    Swal.fire("Gagal", "Terjadi kesalahan saat menyimpan data", "error")
  }
}

const updatechecksheetworkresult = async () => {
  try {
    const response = await Swal.fire({
      title: 'Konfirmasi',
      html: `Apakah anda yakin akan ubah data ini?`,
      icon: 'question',
      showCancelButton: true,
      showCloseButton: true,
      confirmButtonText: 'Ya, ubah',
      cancelButtonText: 'Batal',
    });

    if (!response.isConfirmed) return;

    Swal.fire({
      title: 'Sedang menyimpan data...',
      didOpen: () => {
        Swal.showLoading();
      },
      allowOutsideClick: false,
    });

    await form2.patch(route('checksheet-workresult.update', form2.id), {
      onSuccess: () => {
        Swal.fire({
          icon: 'success',
          title: 'Data berhasil diperbarui!',
          timer: 1500,
          showConfirmButton: false,
        });
      },
      onError: () => {
        Swal.fire({
          icon: 'error',
          title: 'Terjadi kesalahan!',
          text: 'Gagal menyimpan data.',
        });
      },
    });
  } catch (error) {
    console.error(error);
    Swal.fire({
      icon: 'error',
      title: 'Kesalahan!',
      text: 'Terjadi kesalahan tak terduga.',
    });
  }
};

// const canApprove = computed(() => {
//   const result = props.checksheetday?.checksheetworkresult
//   if (!result || !user?.id) return false

//   return (
//     (result.operator_by1 === user.id && !result.operator_at1) ||
//     (result.operator_by2 === user.id && !result.operator_at2) ||
//     (result.operator_by3 === user.id && !result.operator_at3) ||
//     (result.operator_by4 === user.id && !result.operator_at4)
//   )
// })

const canApprove = computed(() => {
  const result = props.checksheetday?.checksheetworkresult
  const report = props.report

  if (!result || !report || !user?.id) return false

  if (user.id !== report.created_by_id) return false

  const pendingApproval =
    (result.operator_by1 && !result.operator_at1) ||
    (result.operator_by2 && !result.operator_at2) ||
    (result.operator_by3 && !result.operator_at3) ||
    (result.operator_by4 && !result.operator_at4)

  return pendingApproval
})

const canChangeMode = computed(() => {
  const result = props.checksheetday?.checksheetworkresult
  const report = props.report

  if (!result || !report || !user?.id) return false

  if (user.id !== report.created_by_id) return false

  if (result.mode) return false

  const pendingApproval =
    (result.operator_by1 && result.operator_at1) ||
    (result.operator_by2 && result.operator_at2) ||
    (result.operator_by3 && result.operator_at3) ||
    (result.operator_by4 && result.operator_at4)

  return pendingApproval 
})

const approvechecksheetworkresult = async () => {
  // const result = props.checksheetday?.checksheetworkresult
  const result = localChecksheetDay.value?.checksheetworkresult;
  if (!result) return

  const confirm = await Swal.fire({
    title: "Apakah Anda yakin?",
    text: "Anda akan menyetujui data ini.",
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Ya, setujui",
    cancelButtonText: "Batal",
  });

  if (!confirm.isConfirmed) return 

  try {
    const res = await axios.post(route("checksheet-workresult.approve"), {
      id: result.id,
    })

    const now = new Date().toISOString()
    if (!result.operator_at1) {
        result.operator_at1 = now;
    } else if (!result.operator_at2) {
        result.operator_at2 = now;
    } else if (!result.operator_at3) {
        result.operator_at3 = now;
    }

    Swal.fire({
      icon: "success",
      title: res.data.message || "Berhasil disetujui!",
      timer: 1500,
      showConfirmButton: false,
    })
  } catch (error) {
    console.error(error)
    Swal.fire({
      icon: "error",
      title: "Gagal approve!",
      text: error.response?.data?.message || error.message || "Terjadi kesalahan saat menyetujui.",
    })
  }
}

const setMode = async (mode) => {
  try {
    const confirm = await Swal.fire({
      title: "Lanjut Mode",
      text: `Proses selanjutnya adalah ${mode}`,
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Lanjut",
      cancelButtonText: "Batal",
    });

    if (!confirm.isConfirmed) return;

    Swal.fire({
      title: "Menyimpan...",
      didOpen: () => Swal.showLoading(),
      allowOutsideClick: false,
    });

    await axios.post(route("checksheet-workresult.setmode"), {
      id: form2.id,  
      working_report_id: form2.working_report_id,
      check_sheet_day_id: form2.check_sheet_day_id,
      mode: mode,
    });

    Swal.fire({
      icon: "success",
      title: "Mode berhasil diperbarui!",
      timer: 1500,
      showConfirmButton: false,
    });

    setTimeout(() => window.location.reload(), 1000);

    form2.mode = mode;

  } catch (error) {
    console.error(error);
    Swal.fire({
      icon: "error",
      title: "Gagal update mode!",
    });
  }
};

const submitwarmingup = () => {
  Swal.fire({
    title: 'Menyimpan data...',
    didOpen: () => Swal.showLoading(),
    allowOutsideClick: false,
  })

  form3.post(route('warming-up.store', props.report.id), {
    onSuccess: () => {
      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: 'Data berhasil disimpan.',
        timer: 1500,
        showConfirmButton: false,
      })
      setTimeout(() => window.location.reload(), 1000);
    },
    onError: () => {
      Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: 'Terjadi kesalahan saat menyimpan data.',
      })
    },
  })
}

const updatewarmingup = async () => {
  try {
    const response = await Swal.fire({
      title: 'Konfirmasi',
      html: `Apakah anda yakin akaan ubah data ini?`,
      icon: 'question',
      showCancelButton: true,
      showCloseButton: true,
    });

    if (response.isConfirmed) {
      Swal.fire({
        title: 'Sedang menyimpan data...',
        didOpen: () => {
          Swal.showLoading();
        },
        allowOutsideClick: false,
      });

      const result = await form3.patch(route('warming-up.update', form3.id), {
        onSuccess: () => {
          close();
          Swal.showLoading();
          Swal.fire({
            title: 'Data berhasil diupdate!',
            icon: 'success',
          });
        },
        onError: () => {
          Swal.showLoading();
          Swal.fire({
            title: 'Terjadi kesalahan!',
            text: 'Gagal menyimpan data.',
            icon: 'error',
          });
        },
      });

      return result;
    }
  } catch (error) {
    console.error(error);
  }
};

const canApproveWarmingUp = computed(() => {
  const result = props.warmingup

  if (!result || !user?.id) return false

  if (user.id !== props.warmingup.created_by_id) return false

  const pendingApproval =
    (result.operator_by1 && !result.operator_at1) ||
    (result.operator_by2 && !result.operator_at2) ||
    (result.operator_by3 && !result.operator_at3) 

  return pendingApproval
})

const approvewarmingup = async (index) => {
    Swal.fire({
        title: 'Memproses...',
        didOpen: () => Swal.showLoading(),
        allowOutsideClick: false,
    });

    try {
        await axios.post(route('warming-up.approve'), {
            id: props.warmingup.id,
            index: index
        });

        const now = new Date().toISOString();

        if (index === 1) props.warmingup.operator_at1 = now;
        if (index === 2) props.warmingup.operator_at2 = now;
        if (index === 3) props.warmingup.operator_at3 = now;

        Swal.fire({
            icon: 'success',
            title: `Approve ${index} berhasil.`,
            timer: 1200,
            showConfirmButton: false,
        });

    } catch (e) {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Terjadi kesalahan saat approve.',
        });
    }
};


// const approvewarmingup = async () => {
//   const result = props.warmingup
//   if (!result) return

//   const confirm = await Swal.fire({
//     title: "Apakah Anda yakin?",
//     text: "Anda akan menyetujui data ini.",
//     icon: "question",
//     showCancelButton: true,
//     confirmButtonColor: "#3085d6",
//     cancelButtonColor: "#d33",
//     confirmButtonText: "Ya, setujui",
//     cancelButtonText: "Batal",
//   });
//     setTimeout(() => window.location.reload(), 1000);

//   if (!confirm.isConfirmed) return 

//   try {
//     const res = await axios.post(route("warming-up.approve"), {
//       id: result.id,
//     })

//     const now = new Date().toISOString()
//     if (result.operator_by1 === user.id) result.operator_at1 = now
//     if (result.operator_by2 === user.id) result.operator_at2 = now
//     if (result.operator_by3 === user.id) result.operator_at3 = now

//     Swal.fire({
//       icon: "success",
//       title: res.data.message || "Berhasil disetujui!",
//       timer: 1500,
//       showConfirmButton: false,
//     })
//   } catch (error) {
//     console.error(error)
//     Swal.fire({
//       icon: "error",
//       title: "Gagal approve!",
//       text: error.response?.data?.message || error.message || "Terjadi kesalahan saat menyetujui.",
//     })
//   }
// }

const totalDistance = computed(() => {
    return (
        (parseFloat(form4.jumlah1) || 0) +
        (parseFloat(form4.jumlah2) || 0) +
        (parseFloat(form4.jumlah3) || 0)
    )
})

const totalWesel = computed(() => {
    return (
        (parseInt(form4.jumlah_wesel1) || 0) +
        (parseInt(form4.jumlah_wesel2) || 0) +
        (parseInt(form4.jumlah_wesel3) || 0)
    )
})

watch(totalDistance, (val) => form4.total_distance = val)
watch(totalWesel, (val) => form4.total_wesel = val)

const submitworkresult = () => {
  Swal.fire({
    title: 'Menyimpan data...',
    didOpen: () => Swal.showLoading(),
    allowOutsideClick: false,
  })

  form4.post(route('work-results.store', props.report.id), {
    onSuccess: () => {
      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: 'Laporan kerja berhasil disimpan.',
        timer: 1500,
        showConfirmButton: false,
      })
    },
    onError: () => {
      Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: 'Terjadi kesalahan saat menyimpan data.',
      })
    },
  })
}

const updateworkresult = async () => {
  try {
    const response = await Swal.fire({
      title: 'Konfirmasi',
      html: `Apakah anda yakin akaan ubah data ini?`,
      icon: 'question',
      showCancelButton: true,
      showCloseButton: true,
    });

    if (response.isConfirmed) {
      Swal.fire({
        title: 'Sedang menyimpan data...',
        didOpen: () => {
          Swal.showLoading();
        },
        allowOutsideClick: false,
      });

      const result = await form4.patch(route('work-results.update', form4.id), {
        preserveScroll: true,
        preserveState: false,
        onSuccess: () => {
          close();
          Swal.showLoading();
          Swal.fire({
            title: 'Data berhasil diupdate!',
            icon: 'success',
          });
        },
        onError: () => {
          Swal.showLoading();
          Swal.fire({
            title: 'Terjadi kesalahan!',
            text: 'Gagal menyimpan data.',
            icon: 'error',
          });
        },
      });

      return result;
    }
  } catch (error) {
    console.error(error);
  }
};

const submitForm = () => {
  Swal.fire({
      title: 'Menyimpan data...',
      didOpen: () => Swal.showLoading(),
      allowOutsideClick: false,
  });

  const payload = {
    working_report_id: props.report.id,

    mg1: form13.data(),
    mg2: form14.data(),
    mg3: form15.data(),
    perekaman: form16.data(),
  };

  axios.post(route('working-results.submit-form'), payload)
  .then((res) => {
      Swal.fire({
          icon: 'success',
          title: 'Berhasil!',
          text: 'Data berhasil disimpan.',
          timer: 1000,
          showConfirmButton: false,
      });
      
      setTimeout(() => window.location.reload(), 1000);
  });
};

const canApproveWorkResult = computed(() => {
  const workresult = props.report?.workresult
  if (!workresult || !user?.id) return false

  return (
    (workresult.operator_by1 === user.id && !workresult.operator_at1) ||
    (workresult.operator_by2 === user.id && !workresult.operator_at2) ||
    (workresult.operator_by3 === user.id && !workresult.operator_at3) 
  )
})

const approveworkresult = async () => {
  const workresult = props.report?.workresult
  if (!workresult) return

  const confirm = await Swal.fire({
    title: "Apakah Anda yakin?",
    text: "Anda akan menyetujui data ini.",
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Ya, setujui",
    cancelButtonText: "Batal",
  })

  if (!confirm.isConfirmed) return 

  try {
    const res = await axios.post(route("workresult.approve"), {
      id: workresult.id,
    })

    const now = new Date().toISOString()
    if (workresult.operator_by1 === user.id) workresult.operator_at1 = now
    if (workresult.operator_by2 === user.id) workresult.operator_at2 = now
    if (workresult.operator_by3 === user.id) workresult.operator_at3 = now

    Swal.fire({
      icon: "success",
      title: res.data.message || "Berhasil disetujui!",
      timer: 1500,
      showConfirmButton: false,
    })
  } catch (error) {
    console.error(error)
    Swal.fire({
      icon: "error",
      title: "Gagal approve!",
      text: error.response?.data?.message || error.message || "Terjadi kesalahan saat menyetujui.",
    })
  }
}

const currentSection = ref('report');

const fetch = async (section = 'report', report) => {
  const reportId = typeof report === 'object' ? report.id : report;

  if (!reportId) {
    console.error('Report tidak ditemukan');
    return;
  }

  Swal.showLoading();

  try {
    const response = await axios.post(
      route('working-reports.fetch', reportId),
      { section }
    );
    const data = response.data;
    Swal.close();

    switch (section) {
      case 'report':
        form.first_section = data.first_section;
        break;
      case 'checksheetday':
        form1.checksheetday = data.checksheetday;
        break;
      case 'warmingup':
        form3.warmingup = data.warmingup;
        break;
      case 'upload':
        form5.upload = data.upload;
        break;
      case 'workresult':
        form4.workresult = data.workresult;
        break;
      case 'workresultok':
        form4.workresult = data.workresult;
        break;
    }

    form.report = data.report;
    currentSection.value = section;
  } catch (error) {
    console.error(error);
    Swal.close();
  }
};

const shouldShowGroup = (item, index) => {
  if (index === 0) return true;
  return form1.results[index - 1].group_name !== item.group_name;
};

const getGroupCount = (groupName) => {
  return form1.results.filter(i => i.group_name === groupName).length;
};

const isLastInGroup = (index) => {
  const current = form1.results[index];
  const next = form1.results[index + 1];

  return !next || next.group_name !== current.group_name;
};

const hasWorkingOrder = computed(() => !!props.report.id);

const hasCheckSheet = computed(() => !!props.checksheetworkresult)

const isWarmingUpMode = computed(() => {
  const result = props.checksheetworkresult
  return result?.mode === 'warmingup'
})

const isWorkingOrderMode = computed(() => {
  const result = props.checksheetworkresult
  return result?.mode === 'working'
})

const isWorkResult = computed(() => {
  const result = props.checksheetworkresult
  // return result?.mode === 'working'
  return ['working', 'warmingup'].includes(result?.mode)
})

const hasWorkResultAccess = computed(() => {
  return !!props.checksheetworkresult && !!props.upload
})

onMounted(() => {
  form.reported_date = new Date().toISOString().slice(0, 10);
});

const formatDate = (date) => {
  if (!date) return '-'
  return new Date(date).toOnlyIndonesianDate()
}

const formatDateDay = (dateString) => {
    if (!dateString || dateString === '-') return '-';

    const date = new Date(dateString);
    const options = { 
        weekday: 'long', 
        day: 'numeric',  
        month: 'long',   
        year: 'numeric', 
    };
    
    return new Intl.DateTimeFormat('id-ID', options).format(date);
};

const esc = e => e.key === 'Escape' && close()
onMounted(() => window.addEventListener('keydown', esc))
onUnmounted(() => window.removeEventListener('keydown', esc))
</script>

<style src="@vueform/multiselect/themes/default.css"></style>
<style src="@/multiselect.css"></style>

<template>
  
  <DashboardLayout :title="__('Working Order')">
    <div class="flex flex-col space-y-2 border rounded bg-white dark:bg-white dark:text-black">
      <div class="flex flex-row overflow-x-auto md:overflow-x-visible space-x-0.5 w-full rounded">
        <div 
          class="border rounded p-1 text-center text-sm flex-1 flex-shrink" 
          :class="{ 
              'bg-blue-700 text-white font-bold': currentSection === 'report',
              'bg-blue-500 text-white font-bold': currentSection !== 'report' && report.id,
              'bg-red-300 text-black': currentSection !== 'report' && !report.id
          }"
          @click.prevent="fetch('report', report)" 
        >
          <a href="#list-report" id="list-report-list" role="tab" aria-controls="report">
            <p class="text-[11px] font-bold">Working Order</p>
          </a>
        </div>

        <div 
          class="border rounded p-1 text-center text-sm flex-1 flex-shrink" 
          :class="{ 
              'bg-blue-700 text-white font-bold': currentSection === 'checksheetday' && hasWorkingOrder,
              'bg-blue-500 text-white font-bold': currentSection !== 'checksheetday' && report.checksheetday?.checksheetworkresult?.id && hasWorkingOrder,
              'bg-red-500 text-black cursor-not-allowed': !hasWorkingOrder,
              'bg-red-500 text-black': currentSection !== 'checksheetday' && !report.checksheetday?.checksheetworkresult?.id && hasWorkingOrder
          }"
          @click.prevent="hasWorkingOrder && fetch('checksheetday', report)" 
        >
          <a href="#list-checksheetday" id="list-checksheetday-list" role="tab" aria-controls="checksheetday" :class="!hasWorkingOrder"
          >
            <div class="flex items-center justify-center space-x-0.5 md:space-x-1">
              <Icon v-if="!hasWorkingOrder" name="lock" class="w-3 h-4 md:w-3 md:h-3 text-black" />
              <p class="text-[11px] font-bold">Daily Check</p>
            </div>
          </a>
        </div>

        <div
          class="border rounded p-1 text-center text-sm flex-1 flex-shrink"
          :class="{
            'bg-blue-700 text-white font-bold': currentSection === 'warmingup' && isWarmingUpMode,
            'bg-blue-500 text-white font-bold': currentSection !== 'warmingup' && report.warmingup?.id && isWarmingUpMode,
            'bg-red-500 text-black cursor-not-allowed': !isWarmingUpMode,
            'bg-red-500 text-black': currentSection !== 'warmingup' && !report.warmingup?.id && isWarmingUpMode
          }"
          @click.prevent="isWarmingUpMode && fetch('warmingup', report)"
        >
          <a href="#list-warmingup" id="list-warmingup-list" role="tab" aria-controls="warmingup" :class="!report.sectionFiveOpen || !isWarmingUpMode "
          >
            <div class="flex items-center justify-center space-x-0.5 md:space-x-1">
              <Icon v-if="!isWarmingUpMode" name="lock" class="w-3 h-4 md:w-3 md:h-3 text-black" />
              <p class="text-[11px] font-bold">Warming Up</p>
            </div>
          </a>
        </div>

        <!-- <div
          class="border rounded p-1 text-center text-sm flex-1 flex-shrink"
          :class="{
            'bg-blue-700 text-white font-bold': currentSection === 'upload' && hasCheckSheet,
            'bg-blue-500 text-white font-bold': currentSection !== 'upload' && report.upload?.id && hasCheckSheet,
            'bg-red-500 text-black cursor-not-allowed': !hasCheckSheet,
            'bg-red-500 text-black': currentSection !== 'upload' && !report.upload?.id && hasCheckSheet
          }"
          @click.prevent="hasCheckSheet && fetch('upload', report)"
        >
          <a href="#list-upload" id="list-upload-list" role="tab" aria-controls="upload" :class="!report.sectionFiveOpen || !hasCheckSheet "
          >
            <div class="flex items-center justify-center space-x-0.5 md:space-x-1">
              <Icon v-if="!hasCheckSheet" name="lock" class="w-3 h-4 md:w-3 md:h-3 text-black" />
              <p class="text-[11px] font-bold">Upload</p>
            </div>
          </a>
        </div> -->

        <div 
          class="border rounded p-1 text-center text-sm flex-1 flex-shrink" 
            :class="{ 
              'bg-blue-700 text-white font-bold': currentSection === 'workresult',
              'bg-blue-500 text-white font-bold': currentSection !== 'workresult' && report.workresult?.id && isWorkingOrderMode,
              'bg-red-500 text-black cursor-not-allowed': !isWorkingOrderMode,
              'bg-red-500 text-black': currentSection !== 'workresult' && !report.workresult?.id && isWorkingOrderMode
            }"
            @click.prevent="isWorkingOrderMode && fetch('workresult', report)"
        >
          <a href="#list-workresult" id="list-workresult-list" role="tab" aria-controls="workresult" :class="!isWorkingOrderMode ">
            <div class="flex items-center justify-center space-x-0.5 md:space-x-1">
              <Icon v-if="!isWorkingOrderMode" name="lock" class="w-3 h-4 md:w-3 md:h-3 text-black" />
              <p class="text-[11px] font-bold">Working</p>
            </div>
          </a>
        </div>

        <!-- <div 
            v-if="isWorkResult"
            class="border rounded p-1 text-center text-sm flex-1 flex-shrink" 
            :class="{ 
              'bg-blue-700 text-white font-bold': currentSection === 'workresultok',
              'bg-blue-500 text-white font-bold': currentSection !== 'workresultok' && (report.workresult?.id || warmingup?.id),
              'bg-red-500 text-white font-bold': currentSection !== 'workresultok' && (!report.workresult?.id || warmingup?.id),
            }"
            @click.prevent="fetch('workresultok', report)"
        >
          <a 
            href="#list-workresultok" 
            id="list-workresultok-list" 
            role="tab" 
            aria-controls="workresultok">
            <div class="flex items-center justify-center space-x-0.5 md:space-x-1">
              <p class="text-[11px] font-bold">Work Result</p>
            </div>
          </a>
        </div>         -->

        <div 
          v-if="isWorkResult"
          class="border rounded p-1 text-center text-sm flex-1 flex-shrink transition-colors duration-200" 
          :class="{ 
            'bg-blue-700 text-white font-bold': currentSection === 'workresultok',
            'bg-red-500 text-black cursor-not-allowed': !report.workresult?.id && !warmingup?.id,
            'bg-blue-500 text-white font-bold': currentSection !== 'workresultok' && (report.workresult?.id || warmingup?.id),
          }"
          @click.prevent="(report.workresult?.id || warmingup?.id) && fetch('workresultok', report)"
        >
          <a 
            href="#list-workresultok" 
            id="list-workresultok-list" 
            role="tab" 
            aria-controls="workresultok">
            <div class="flex items-center justify-center space-x-0.5 md:space-x-1">
              <Icon v-if="!report.workresult?.id && !warmingup?.id" name="lock" class="w-3 h-4 md:w-3 md:h-3 text-black" />
              <p class="text-[11px] font-bold">Work Result</p>
            </div>
          </a>
        </div>
        <!-- END -->

      </div>
    </div>
    <Card class="border bg-white dark:bg-white dark:text-black">
      <template #body>
      <div class="flex flex-col space-y-2 p-1">     
        <div class="flex flex-col md:flex-row mt-2 md:space-y-0 md:space-x-0">
          <div class=" p-2 w-full" id="list-section1" role="tabpanel" aria-labelledby="list-section1-list">
          <div class="flex flex-col space-y-2"> 
            
            <!-- section working report -->             
					  <div v-if="currentSection === 'report'" class="tab-pane fade show active" id="list-report" role="tabpanel" aria-labelledby="list-report-list">
                <!-- <div class="grid grid-cols-1 gap-2 mb-4 md:grid-cols-2"> -->
                <div class="grid grid-cols-1 gap-4 mb-4 md:grid-cols-2">

                  <!-- <div class="flex items-center text-xs border-b border-gray-200 py-1"> -->
                  <div class="flex flex-col items-start space-y-1">
                    <label for="machine_id" class="font-bold text-xs">
                      {{ __('Nama Mesin') }}
                    </label>
                  
                    <div class="w-full">
                      <Select
                        v-model="form.machine_id"
                        :options="machines.map(machine => ({
                          label: `${machine.name} - ${machine.type} - ${machine.nomor} - ${machine.no_sarana} (${machine.region.name})`,
                          value: machine.id,
                        }))"
                        :searchable="true"
                        placeholder="Pilih Mesin"
                        class="w-full border-none text-center text-xs"
                        style="font-size: 0.7rem;"
                        disabled
                      >
                        <template #option="{ option }">
                          <span class="text-xs antialiased">
                              {{ option.label }}
                          </span>
                        </template>
                      </Select>
                      <InputError :error="form.errors.machine_id" />
                    </div>
                    <!-- <div class="w-32 font-bold text-xs">Nama Mesin</div>
                    <div class="pr-2 text-xs">:</div>
                    <div> {{ report?.machine.name }} - {{ report?.machine.type }}- {{ report?.machine.nomor }} - {{ report?.machine.no_sarana }} ({{ report?.machine?.region.name }})</div> -->
                  </div>
                  
                  <div class="flex flex-col items-start space-y-1">
                    <label for="region_id" class="font-bold text-xs">
                      {{ __('Nama Wilayah') }}
                    </label>
                    
                    <div class="w-full">
                      <Select
                        v-model="form.region_id"
                        :options="regions.map(region => ({
                          label: region.name,
                          value: region.id,
                        }))"
                        :searchable="true"
                        placeholder="Pilih Wilayah"
                        class="w-full border-none text-center text-xs"
                        style="font-size: 0.7rem;"
                      >
                        <template #option="{ option }">
                          <span class="text-xs antialiased">
                              {{ option.label }}
                          </span>
                        </template>
                      </Select>
                      <InputError :error="form.errors.region_id"/>
                    </div>
                    <!-- <div class="w-32 font-bold text-xs">Nama Wilayah</div>
                    <div class="pr-2 text-xs">:</div>
                    <div> {{ report?.machine?.region.name || report?.region.name || '-' }}</div> -->
                  </div>
                  
                  <div class="flex flex-col items-start space-y-1">
                    <label for="date" class="font-bold text-xs">
                      {{ __('Tanggal') }}
                    </label>
                    
                    <div class="w-full">
                      <Input
                        v-model="form.date"
                        :placeholder="__('Tanggal')"
                        type="datetime-local"
                        class="text-xs"
                      />
                      <InputError :error="form.errors.date"/>
                    </div>
                    <!-- <div class="w-32 font-bold text-xs">Tanggal</div>
                    <div class="pr-2 text-xs">:</div>
                    <div>{{ formatDate(report?.date || '-' ) }}</div> -->
                  </div>
                  
                  <div class="flex flex-col items-start space-y-1">
                    <label for="cuaca" class="font-bold text-xs">
                      {{ __('Cuaca') }}
                    </label>
                    
                    <div class="w-full">
                      <Input
                        v-model="form.cuaca"
                        :placeholder="__('Cuaca')"
                        type="text"
                        class="text-xs"
                      />
                      <InputError :error="form.errors.cuaca"/>
                    </div>
                    <!-- <div class="w-32 font-bold text-xs">Cuaca</div>
                    <div class="pr-2 text-xs">:</div>
                    <div> {{ report?.cuaca || '-' }}</div> -->
                  </div>
                  
                  <div class="flex flex-col items-start space-y-1">
                    <label for="jenis_kpjr" class="font-bold text-xs">
                      {{ __('Jenis KPJR') }}
                    </label>
                    
                    <div class="w-full">
                      <Input
                        v-model="form.jenis_kpjr"
                        :placeholder="__('Jenis KPJR')"
                        type="text"
                        class="text-xs"
                      />
                      <InputError :error="form.errors.jenis_kpjr"/>
                    </div>
                    <!-- <div class="w-32 font-bold text-xs">Jenis KPJR</div>
                    <div class="pr-2 text-xs">:</div>
                    <div> {{ report?.jenis_kpjr || '-' }}</div> -->
                  </div>
                  
                  <div class="flex flex-col items-start space-y-1">
                    <label for="nomor_mesin" class="font-bold text-xs">
                      {{ __('Nomor Mesin') }}
                    </label>
                    
                    <div class="w-full">
                      <Input
                        v-model="form.nomor_mesin"
                        :placeholder="__('Nomor Mesin')"
                        type="text"
                        class="text-xs"
                      />
                      <InputError :error="form.errors.nomor_mesin"/>
                    </div>
                      <!-- <div class="w-32 font-bold text-xs">Nomor Mesin</div>
                      <div class="pr-2 text-xs">:</div>
                      <div> {{ report?.nomor_mesin || '-' }}</div> -->
                  </div>
                  
                  <div class="flex flex-col items-start space-y-1">
                    <label for="nomor_sarana" class="font-bold text-xs">
                      {{ __('Nomor Sarana') }}
                    </label>
                    
                    <div class="w-full">
                      <Input
                        v-model="form.nomor_sarana"
                        :placeholder="__('Nomor Sarana')"
                        type="text"
                        class="text-xs"
                      />
                      <InputError :error="form.errors.nomor_sarana"/>
                    </div>
                    <!-- <div class="w-32 font-bold text-xs">Nomor Sarana</div>
                    <div class="pr-2 text-xs">:</div>
                    <div> {{ report?.nomor_sarana || '-' }}</div> -->
                  </div>
                  
                  <div class="flex flex-col items-start space-y-1">
                      <div class="w-32 font-bold text-xs">Status</div>
                      <div :class="[
                          'px-1 py-1 rounded text-xs font-semibold',
                          {
                              'bg-gray-100 text-gray-800': report.status === 'draft',
                              'bg-yellow-100 text-yellow-800': report.status === 'checksheet_done',
                              'bg-blue-100 text-blue-800': report.status === 'photo_uploaded',
                              'bg-green-500 text-white': report.status === 'finished',
                              'bg-blue-100 text-blue-800': report.status === 'work_done',
                              'bg-green-100 text-green-800': report.status === 'selesai',
                          },
                          ]"
                      >
                          {{
                          report.status === 'draft'
                              ? 'Process Working Order'
                              : report.status === 'checksheet_done'
                              ? 'Process Checksheet'
                              : report.status === 'photo_uploaded'
                              ? 'Process Upload'
                              : report.status === 'work_done'
                              ? 'Process Work Result'
                              : report.status === 'finished'
                              ? 'Approve KUPT'
                              : report.status === 'selesai'
                              ? 'Selesai'
                              : report.status
                          }}</div>
                  </div>
                </div>
                
                <div v-if="form.mode === 'working'" class="p-1">                  
                  <div class="text-sm font-extrabold border-gray-200 py-1 py-1">A. DATA UPLOAD</div>     
                  <p class="text-sm font-semibold border-gray-200 py-1">
                      1. Data Opname Resor Jalan Rel (Awal)
                  </p>

                  <div class="space-y-1">
                      
                      <div class="p-2 border border-gray-200 rounded-lg shadow-sm hover:border-sky-500 transition duration-150">
                          <div class="flex flex-row items-start justify-between text-sm"> 
                              
                              <label for="mg1_awal" class="flex-1 text-xs text-black font-semi-bold pr-2">
                                  a. MG 1 (Lurusan) 
                              </label>

                              <div class="flex space-x-4 flex-shrink-0 text-xs">
                                  
                                  <label 
                                      class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                      :class="{'text-sky-600 font-semibold': form7.ada === '1', 'text-gray-500': form7.ada !== '1'}"
                                  >
                                      <input 
                                          type="checkbox" 
                                          v-model="form7.ada" 
                                          true-value="1" 
                                          false-value="0"
                                          class="rounded text-sky-600 focus:ring-sky-500 w-3 h-3"
                                      >
                                      <span>Ada</span>
                                  </label>
                                  
                                  <label 
                                      class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                      :class="{'text-red-600 font-semibold': form7.tidak === '1', 'text-gray-500': form7.tidak !== '1'}"
                                  >
                                      <input 
                                          type="checkbox" 
                                          v-model="form7.tidak" 
                                          true-value="1" 
                                          false-value="0"
                                          class="rounded text-red-600 focus:ring-red-500 w-3 h-3"
                                      >
                                      <span>Tidak</span>
                                  </label>
                              </div>
                          </div>

                          <div class="mt-2 px-3">
                            <AttachmentInline 
                              :model="mglurusanawal ?? {}" 
                              type="MgLurusanAwal" 
                              :redaction="`Lampiran (MG 1 Lurusan Awal)`"
                              :attachments="mglurusanawal_attachments" 
                            />
                          </div>
                      </div>

                      <div class="p-2 border border-gray-200 rounded-lg shadow-sm hover:border-sky-500 transition duration-150">
                        <div class="flex flex-row items-start justify-between text-sm"> 
                            
                            <label for="mg1_awal" class="flex-1 text-xs text-black font-semi-bold pr-2">
                                a. MG 2 (Lengkungan)
                            </label>

                            <div class="flex space-x-4 flex-shrink-0 text-xs">
                                
                                <label 
                                    class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                    :class="{'text-sky-600 font-semibold': form8.ada === '1', 'text-gray-500': form8.ada !== '1'}"
                                >
                                    <input 
                                        type="checkbox" 
                                        v-model="form8.ada" 
                                        true-value="1" 
                                        false-value="0"
                                        class="rounded text-sky-600 focus:ring-sky-500 w-3 h-3"
                                    >
                                    <span>Ada</span>
                                </label>
                                
                                <label 
                                    class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                    :class="{'text-red-600 font-semibold': form8.tidak === '1', 'text-gray-500': form8.tidak !== '1'}"
                                >
                                    <input 
                                        type="checkbox" 
                                        v-model="form8.tidak" 
                                        true-value="1" 
                                        false-value="0"
                                        class="rounded text-red-600 focus:ring-red-500 w-3 h-3"
                                    >
                                    <span>Tidak</span>
                                </label>
                            </div>
                        </div>

                        <div class="mt-2 px-3">
                          <AttachmentInline 
                            :model="mglengkunganawal ?? {}" 
                            type="MgLengkunganAwal" 
                            :redaction="`Lampiran (MG 2 Lengkungan Awal)`"
                            :attachments="mglengkunganawal_attachments" 
                          />
                        </div>
                      </div>

                      <div class="p-2 border border-gray-200 rounded-lg shadow-sm hover:border-sky-500 transition duration-150">
                        <div class="flex flex-row items-start justify-between text-sm"> 
                            
                            <label for="mg1_awal" class="flex-1 text-xs text-black font-semi-bold pr-2">
                                a. MG 3 (Wesel)
                            </label>

                            <div class="flex space-x-4 flex-shrink-0 text-xs">
                                
                                <label 
                                    class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                    :class="{'text-sky-600 font-semibold': form9.ada === '1', 'text-gray-500': form9.ada !== '1'}"
                                >
                                    <input 
                                        type="checkbox" 
                                        v-model="form9.ada" 
                                        true-value="1" 
                                        false-value="0"
                                        class="rounded text-sky-600 focus:ring-sky-500 w-3 h-3"
                                    >
                                    <span>Ada</span>
                                </label>
                                
                                <label 
                                    class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                    :class="{'text-red-600 font-semibold': form9.tidak === '1', 'text-gray-500': form9.tidak !== '1'}"
                                >
                                    <input 
                                        type="checkbox" 
                                        v-model="form9.tidak" 
                                        true-value="1" 
                                        false-value="0"
                                        class="rounded text-red-600 focus:ring-red-500 w-3 h-3"
                                    >
                                    <span>Tidak</span>
                                </label>
                            </div>
                        </div>

                        <div class="mt-2 px-3">
                          <AttachmentInline 
                            :model="mgweselawal ?? {}" 
                            type="MgWeselAwal" 
                            :redaction="`Lampiran (MG 3 Wesel Awal)`"
                            :attachments="mgweselawal_attachments" 
                          />
                        </div>
                      </div>

                  </div>

                  <p class="text-sm font-semibold border-gray-200 py-1">
                      2. Data Pemeriksaan Silang
                  </p>

                  <div class="space-y-1">
                      
                      <div class="p-2 border border-gray-200 rounded-lg shadow-sm hover:border-sky-500 transition duration-150">
                        <div class="flex flex-row items-start justify-between text-sm"> 
                            
                            <label for="mg1_awal" class="flex-1 text-xs text-black font-semi-bold pr-2">
                                a. KPJR
                            </label>

                            <div class="flex space-x-4 flex-shrink-0 text-xs">
                                
                                <label 
                                    class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                    :class="{'text-sky-600 font-semibold': form10.ada === '1', 'text-gray-500': form10.ada !== '1'}"
                                >
                                    <input 
                                        type="checkbox" 
                                        v-model="form10.ada" 
                                        true-value="1" 
                                        false-value="0"
                                        class="rounded text-sky-600 focus:ring-sky-500 w-3 h-3"
                                    >
                                    <span>Ada</span>
                                </label>
                                
                                <label 
                                    class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                    :class="{'text-red-600 font-semibold': form10.tidak === '1', 'text-gray-500': form10.tidak !== '1'}"
                                >
                                    <input 
                                        type="checkbox" 
                                        v-model="form10.tidak" 
                                        true-value="1" 
                                        false-value="0"
                                        class="rounded text-red-600 focus:ring-red-500 w-3 h-3"
                                    >
                                    <span>Tidak</span>
                                </label>
                            </div>
                        </div>

                        <div class="mt-2 px-3">
                          <AttachmentInline 
                            :model="pemeriksaansilangkpjr ?? {}" 
                            type="PemeriksaanSilangKpjr" 
                            :redaction="`Lampiran (Pemeriksaan Silang KPJR)`"
                            :attachments="pemeriksaansilangkpjr_attachments" 
                          />
                        </div>
                      </div>

                      <div class="p-2 border border-gray-200 rounded-lg shadow-sm hover:border-sky-500 transition duration-150">
                        <div class="flex flex-row items-start justify-between text-sm"> 
                            
                            <label for="mg1_awal" class="flex-1 text-xs text-black font-semi-bold pr-2">
                                b. Lahan
                            </label>

                            <div class="flex space-x-4 flex-shrink-0 text-xs">
                                
                                <label 
                                    class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                    :class="{'text-sky-600 font-semibold': form11.ada === '1', 'text-gray-500': form11.ada !== '1'}"
                                >
                                    <input 
                                        type="checkbox" 
                                        v-model="form11.ada" 
                                        true-value="1" 
                                        false-value="0"
                                        class="rounded text-sky-600 focus:ring-sky-500 w-3 h-3"
                                    >
                                    <span>Ada</span>
                                </label>
                                
                                <label 
                                    class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                    :class="{'text-red-600 font-semibold': form11.tidak === '1', 'text-gray-500': form11.tidak !== '1'}"
                                >
                                    <input 
                                        type="checkbox" 
                                        v-model="form11.tidak" 
                                        true-value="1" 
                                        false-value="0"
                                        class="rounded text-red-600 focus:ring-red-500 w-3 h-3"
                                    >
                                    <span>Tidak</span>
                                </label>
                            </div>
                        </div>

                        <div class="mt-2 px-3">
                          <AttachmentInline 
                            :model="pemeriksaansilanglahan ?? {}" 
                            type="PemeriksaanSilangLahan" 
                            :redaction="`Lampiran (Pemeriksaan Silang Lahan)`"
                            :attachments="pemeriksaansilanglahan_attachments" 
                          />
                        </div>
                      </div>

                  </div>

                  <p class="text-sm font-semibold border-gray-200 py-1">
                      3. Data Perekaman (Awal)
                  </p>

                  <div class="space-y-1">
                      
                      <div class="p-2 border border-gray-200 rounded-lg shadow-sm hover:border-sky-500 transition duration-150">
                        <div class="flex flex-row items-start justify-between text-sm"> 
                            
                            <label for="mg1_awal" class="flex-1 text-xs text-black font-semi-bold pr-2">
                                a. Perekaman Awal
                            </label>

                            <div class="flex space-x-4 flex-shrink-0 text-xs">
                                
                                <label 
                                    class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                    :class="{'text-sky-600 font-semibold': form12.ada === '1', 'text-gray-500': form12.ada !== '1'}"
                                >
                                    <input 
                                        type="checkbox" 
                                        v-model="form12.ada" 
                                        true-value="1" 
                                        false-value="0"
                                        class="rounded text-sky-600 focus:ring-sky-500 w-3 h-3"
                                    >
                                    <span>Ada</span>
                                </label>
                                
                                <label 
                                    class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                    :class="{'text-red-600 font-semibold': form12.tidak === '1', 'text-gray-500': form12.tidak !== '1'}"
                                >
                                    <input 
                                        type="checkbox" 
                                        v-model="form12.tidak" 
                                        true-value="1" 
                                        false-value="0"
                                        class="rounded text-red-600 focus:ring-red-500 w-3 h-3"
                                    >
                                    <span>Tidak</span>
                                </label>
                            </div>
                        </div>

                        <div class="mt-2 px-3">
                          <AttachmentInline 
                            :model="perekamanawal ?? {}" 
                            type="PerekamanAwal" 
                            :redaction="`Lampiran (Perekaman Awal)`"
                            :attachments="perekamanawal_attachments" 
                          />
                        </div>
                      </div>
                  </div>
                </div><br>
                  
                <div class="text-sm font-extrabold border-gray-200 py-1 pl-0">B. DATA OPERASI MESIN</div>   
                <div class="grid grid-cols-1 gap-4 mb-4 md:grid-cols-2">                  
                  <div class="flex flex-col items-start space-y-1">
                    <label for="waktu_start_engine" class="font-bold text-xs">
                      {{ __('Waktu Start Engine') }}
                    </label>
                    
                    <div class="w-full">
                      <Input
                        v-model="form.waktu_start_engine"
                        :placeholder="__('Waktu Start Engine')"
                        type="time"
                        class="text-xs"
                      />
                      <InputError :error="form.errors.waktu_start_engine"/>
                    </div>
                    <!-- <div class="w-32 font-bold text-xs">Waktu Start Engine</div>
                    <div class="pr-2 text-xs">:</div>
                    <div> {{ report?.waktu_start_engine?.slice(0, 5)  || '-' }}</div> -->
                  </div>
                  
                  <div class="flex flex-col items-start space-y-1">
                    <label for="jam_traveling_awal" class="font-bold text-xs">
                      {{ __('Jam Traveling Awal') }}
                    </label>
                    
                    <div class="w-full">
                      <Input
                        v-model="form.jam_traveling_awal"
                        :placeholder="__('Jam Traveling Awal')"
                        type="text"
                        class="text-xs"
                      />
                      <InputError :error="form.errors.jam_traveling_awal"/>
                    </div>
                    <!-- <div class="w-32 font-bold text-xs">Jam Traveling Awal</div>
                    <div class="pr-2 text-xs">:</div>
                    <div> {{ report?.jam_traveling_awal || '-' }}</div> -->
                  </div>
                  
                  <div class="flex flex-col items-start space-y-1">
                    <label for="jam_kerja_awal" class="font-bold text-xs">
                      {{ __('Jam Kerja Awal') }}
                    </label>
                    
                    <div class="w-full">
                      <Input
                        v-model="form.jam_kerja_awal"
                        :placeholder="__('Jam Kerja Awal')"
                        type="time"
                        class="text-xs"
                      />
                      <InputError :error="form.errors.jam_kerja_awal"/>
                    </div>
                    <!-- <div class="w-32 font-bold text-xs">Jam Kerja Awal</div>
                    <div class="pr-2 text-xs">:</div>
                    <div> {{ report?.jam_kerja_awal?.slice(0, 5)  || '-' }}</div> -->
                  </div>
                  
                  <div class="flex flex-col items-start space-y-1">
                    <label for="jam_mesin_awal" class="font-bold text-xs">
                      {{ __('Jam Mesin Awal') }}
                    </label>
                    
                    <div class="w-full">
                      <Input
                        v-model="form.jam_mesin_awal"
                        :placeholder="__('Jam Mesin Awal')"
                        type="text"
                        class="text-xs"
                      />
                      <InputError :error="form.errors.jam_mesin_awal"/>
                    </div>
                    <!-- <div class="w-32 font-bold text-xs">Jam Mesin Awal</div>
                    <div class="pr-2 text-xs">:</div>
                    <div> {{ report?.jam_mesin_awal || '-' }}</div> -->
                  </div>
                  
                  <div class="flex flex-col items-start space-y-1">
                    <label for="jam_generator_awal" class="font-bold text-xs">
                      {{ __('Jam Generator Awal') }}
                    </label>
                    
                    <div class="w-full">
                      <Input
                        v-model="form.jam_generator_awal"
                        :placeholder="__('Jam Generator Awal')"
                        type="text"
                        class="text-xs"
                      />
                      <InputError :error="form.errors.jam_generator_awal"/>
                    </div>
                    <!-- <div class="w-32 font-bold text-xs">Jam Generator Awal</div>
                    <div class="pr-2 text-xs">:</div>
                    <div> {{ report?.jam_generator_awal || '-' }}</div> -->
                  </div>
                  
                  <div class="flex flex-col items-start space-y-1">
                    <label for="counter_tamping_awal" class="font-bold text-xs">
                      {{ __('Counter Tamping Awal') }}
                    </label>
                    
                    <div class="w-full">
                      <Input
                        v-model="form.counter_tamping_awal"
                        :placeholder="__('Counter Tamping Awal')"
                        type="number"
                        class="text-xs"
                      />
                      <InputError :error="form.errors.counter_tamping_awal"/>
                    </div>
                    <!-- <div class="w-32 font-bold text-xs">Counter Tamping Awal</div>
                    <div class="pr-2 text-xs">:</div>
                    <div> {{ report?.counter_tamping_awal || '-' }}</div> -->
                  </div>
                  
                  <div class="flex flex-col items-start space-y-1">
                    <label for="oddometer_awal" class="font-bold text-xs">
                      {{ __('Oddometer Awal') }}
                    </label>
                    
                    <div class="w-full">
                      <Input
                        v-model="form.oddometer_awal"
                        :placeholder="__('Oddometer Awal')"
                        type="number"
                        class="text-xs"
                      />
                      <InputError :error="form.errors.oddometer_awal"/>
                    </div>
                    <!-- <div class="w-32 font-bold text-xs">Oddometer Awal</div>
                    <div class="pr-2 text-xs">:</div>
                    <div> {{ report?.oddometer_awal || '-' }}</div> -->
                  </div>
                  
                  <div class="flex flex-col items-start space-y-1">
                    <label for="hsd_awal_kerja" class="font-bold text-xs">
                      {{ __('HSD Awal Kerja') }}
                    </label>
                    
                    <div class="w-full">
                      <Input
                        v-model="form.hsd_awal_kerja"
                        :placeholder="__('HSD Awal Kerja')"
                        type="number"
                        class="text-xs"
                      />
                      <InputError :error="form.errors.hsd_awal_kerja"/>
                    </div>
                    <!-- <div class="w-32 font-bold text-xs">HSD Awal Kerja</div>
                    <div class="pr-2 text-xs">:</div>
                    <div> {{ report?.hsd_awal_kerja || '-' }}</div> -->
                  </div>
                </div>
                  
                <div class="p-1">
                  <div class="text-sm font-extrabold  border-gray-200 py-1 pl-0">C. DATA PERSONEL</div>     
                  <div class="grid grid-cols-1 gap-4 mb-4 md:grid-cols-2">             
                    <div class="flex flex-col items-start space-y-1">
                      <label for="operator_by1" class="block text-xs font-semibold">
                        {{ __('Operator 1') }}
                      </label>
                      
                      <Select
                        v-model="form.operator_by1"
                        :options="users.filter(user => user.id !== 1).map(user => ({
                          label: `[${user.username}] ${user.name.toUpperCase()}`,
                          value: user.id,
                        }))"
                        :searchable="true"
                        placeholder="Pilih Operator 1"
                        class="w-full border-none text-center text-xs"
                        style="font-size: 0.7rem;"
                      >
                        <template #option="{ option }">
                          <span class="text-xs antialiased">
                              {{ option.label }}
                          </span>
                        </template>
                      </Select>
                      <InputError :error="form.errors.operator_by1" />
                      <!-- <div class="w-32 font-bold text-xs">Operator 1</div>
                      <div class="pr-2 text-xs">:</div>
                      <div class="lowercase first-letter:capitalize">
                        {{ report?.operator1?.name || '-' }} - {{ report?.operator1?.username }}

                        <span class="bg-blue-100 text-black" v-if="report?.operator_at1">
                            (Disetujui Tanggal : {{ formatDate(report.operator_at1) }})
                        </span>
                      </div> -->
                    </div>

                    <div class="flex flex-col items-start space-y-1">
                      <label for="approved_by" class="block text-xs font-semibold">
                        {{ __('Pengawal 1') }}
                      </label>
                      
                      <Select
                        v-model="form.approved_by"
                        :options="users.filter(user => user.id !== 1).map(user => ({
                          label: `[${user.username}] ${user.name.toUpperCase()}`,
                          value: user.id,
                        }))"
                        :searchable="true"
                        placeholder="Pilih Pengawal 1"
                        class="w-full border-none text-center text-xs"
                        style="font-size: 0.7rem;"
                      >
                        <template #option="{ option }">
                          <span class="text-xs antialiased">
                              {{ option.label }}
                          </span>
                        </template>
                      </Select>
                      <InputError :error="form.errors.approved_by" />
                      <!-- <div class="w-32 font-bold text-xs">Pengawal 1</div>
                      <div class="pr-2 text-xs">:</div>
                      <div class="lowercase first-letter:capitalize">
                        {{ report?.pengawal?.name || '-' }} - {{ report?.pengawal?.username }}

                        <span class="bg-blue-100 text-black" v-if="report?.approved_at">
                            (Disetujui Tanggal : {{ formatDate(report.approved_at) }})
                        </span>
                      </div> -->
                    </div>
                              
                    <div class="flex flex-col items-start space-y-1">
                      <label for="operator_by2" class="block text-xs font-semibold">
                        {{ __('Operator 2') }}
                      </label>
                      
                      <Select
                        v-model="form.operator_by2"
                        :options="users.filter(user => user.id !== 1).map(user => ({
                          label: `[${user.username}] ${user.name.toUpperCase()}`,
                          value: user.id,
                        }))"
                        :searchable="true"
                        placeholder="Pilih Operator 2"
                        class="w-full border-none text-center text-xs"
                        style="font-size: 0.7rem;"
                      >
                        <template #option="{ option }">
                          <span class="text-xs antialiased">
                              {{ option.label }}
                          </span>
                        </template>
                      </Select>
                      <InputError :error="form.errors.operator_by2" />
                      <!-- <div class="w-32 font-bold text-xs">Operator 2</div>
                      <div class="pr-2 text-xs">:</div>
                      <div class="lowercase first-letter:capitalize">
                        {{ report?.operator2?.name || '-' }} - {{ report?.operator2?.username }}

                        <span class="bg-blue-100 text-black" v-if="report?.operator_at2">
                            (Disetujui Tanggal : {{ formatDate(report.operator_at2) }})
                        </span>
                      </div> -->
                    </div>

                    <div class="flex flex-col items-start space-y-1">
                      <label for="approved_by1" class="block text-xs font-semibold">
                        {{ __('Pengawal 2') }}
                      </label>
                      
                      <Select
                        v-model="form.approved_by1"
                        :options="users.filter(user => user.id !== 1).map(user => ({
                          label: `[${user.username}] ${user.name.toUpperCase()}`,
                          value: user.id,
                        }))"
                        :searchable="true"
                        placeholder="Pilih Pengawal 2"
                        class="w-full border-none text-center text-xs"
                        style="font-size: 0.7rem;"
                      >
                        <template #option="{ option }">
                          <span class="text-xs antialiased">
                              {{ option.label }}
                          </span>
                        </template>
                      </Select>
                      <InputError :error="form.errors.approved_by1" />
                      <!-- <div class="w-32 font-bold text-xs">Pengawal 2</div>
                      <div class="pr-2 text-xs">:</div>
                      <div class="lowercase first-letter:capitalize">
                        {{ report?.pengawal1?.name || '-' }} - {{ report?.pengawal1?.username }}

                        <span class="bg-blue-100 text-black" v-if="report?.approved_at1">
                            (Disetujui Tanggal : {{ formatDate(report.approved_at1) }})
                        </span>
                      </div> -->
                    </div>
                            
                    <div class="flex flex-col items-start space-y-1">
                      <label for="operator_by3" class="block text-xs font-semibold">
                        {{ __('Operator 3') }}
                      </label>
                      
                      <Select
                        v-model="form.operator_by3"
                        :options="users.filter(user => user.id !== 1).map(user => ({
                          label: `[${user.username}] ${user.name.toUpperCase()}`,
                          value: user.id,
                        }))"
                        :searchable="true"
                        placeholder="Pilih Operator 3"
                        class="w-full border-none text-center text-xs"
                        style="font-size: 0.7rem;"
                      >
                        <template #option="{ option }">
                          <span class="text-xs antialiased">
                              {{ option.label }}
                          </span>
                        </template>
                      </Select>
                      <InputError :error="form.errors.operator_by3" />
                      <!-- <div class="w-32 font-bold text-xs">Operator 3</div>
                      <div class="pr-2 text-xs">:</div>
                      <div class="lowercase first-letter:capitalize">
                        {{ report?.operator3?.name || '-' }} - {{ report?.operator3?.username }}

                        <span class="bg-blue-100 text-black" v-if="report?.operator_at3">
                            (Disetujui Tanggal : {{ formatDate(report.operator_at3) }})
                        </span>
                      </div> -->
                    </div>
                  </div>
                </div>
                
                <!-- <p v-if="report?.operator_at1" class="text-xs font-bold text-blue-600">Operator 1 telah menyetujui pada tanggal : {{ formatDate(report?.operator_at1)}}</p>
                <p v-if="report?.operator_at2" class="text-xs font-bold text-blue-600">Operator 2 telah menyetujui pada tanggal : {{ formatDate(report?.operator_at2)}}</p>
                <p v-if="report?.operator_at3" class="text-xs font-bold text-blue-600">Operator 3 telah menyetujui pada tanggal : {{ formatDate(report?.operator_at3)}}</p>

                <div v-if="report.operator_by1 && !report.operator_at1" class="d-flex justify-content-end mt-3">
                    <Button 
                        class="bg-blue-700 hover:bg-blue-900 float-right mr-2 text-xs"
                        @click.prevent="approve(1)"
                    >
                        Approve Operator 1
                    </Button>
                </div>

                <div v-if="report.operator_by2 && report.operator_at1 && !report.operator_at2" class="d-flex justify-content-end mt-2">
                    <Button 
                        class="bg-blue-700 hover:bg-blue-900 float-right mr-2 text-xs"
                        @click.prevent="approve(2)"
                    >
                        Approve Operator 2
                    </Button>
                </div>

                <div v-if="report.operator_by3 && report.operator_at2 && !report.operator_at3" class="d-flex justify-content-end mt-2">
                    <Button 
                        class="bg-blue-700 hover:bg-blue-900 float-right mr-2 text-xs"
                        @click.prevent="approve(3)"
                    >
                        Approve Operator 3
                    </Button>
                </div>                 -->
            </div>
            <!-- section working report -->

            <!-- section checksheetday -->
            <div v-if="currentSection === 'checksheetday'" class="tab-pane fade" id="list-checksheetday" role="tabpanel" aria-labelledby="list-checksheetday-list">
              <div class="flex flex-col space-y-4" v-if="currentStep === 1">

                <!-- Bagian Informasi Mesin -->
                <!-- <div class="grid grid-cols-2 gap-4 mb-4"> -->
                <div class="grid grid-cols-1 gap-4 mb-4 md:grid-cols-2">
                  <div class="flex flex-col items-start space-y-1">
                    <label for="machine_id" class="font-bold text-xs">
                    {{ __('Nama Mesin') }}
                    </label>
                    
                      <div class="w-full">
                        <Select
                          v-model="form.machine_id"
                          :options="machines.map(machine => ({
                            label: `${machine.name} - ${machine.type} - ${machine.nomor} - ${machine.no_sarana} (${machine.region.name})`,
                            value: machine.id,
                          }))"
                          :searchable="true"
                          placeholder="Pilih Mesin"
                          style="font-size: 0.7rem;"
                          disabled
                          class="w-full border-none text-center text-xs"
                        >
                          <template #option="{ option }">
                            <span class="text-xs antialiased">
                                {{ option.label }}
                            </span>
                          </template>
                        </Select>
                      <InputError :error="form.errors.machine_id" />
                      </div>
                  </div>

                  <!-- <div class="flex flex-col items-start space-y-1">
                    <label class="font-bold text-xs">Jenis / Tipe KPJR</label>
                    <Input v-model="form1.jenis" :placeholder="__('Jenis / Tipe KPJR')" type="text" class="w-full text-xs" />
                    <InputError :error="form1.errors.jenis" />
                  </div> -->

                  <div class="flex flex-col items-start space-y-1">
                    <label class="font-bold text-xs">Hari / Tanggal</label>
                    <Input v-model="form1.tanggal" required type="date" class="w-full text-xs"/>
                    <InputError :error="form1.errors.tanggal" />
                  </div>
                  
                  <div class="flex flex-col items-start space-y-1">
                    <label class="font-bold text-xs">Jam Mesin</label>
                    <Input v-model="form1.jam_mesin" :placeholder="__('Jam Mesin')" required type="time" class="w-full text-xs" />
                    <InputError :error="form1.errors.jam_mesin" />
                  </div>

                  <div class="flex flex-col items-start space-y-1">
                    <label class="font-bold text-xs">Lokasi Pelaksanaan</label>
                    <Input v-model="form1.lokasi" required type="text" :placeholder="__('Lokasi Pelaksanaan')" class="w-full text-xs"/>
                    <InputError :error="form1.errors.lokasi" />
                  </div>

                  <div class="flex flex-col items-start space-y-1">
                    <label class="font-bold text-xs">Counter Pecok</label>
                    <Input v-model="form1.counter_pecok" required type="number" :placeholder="__('Counter Pecok')" class="w-full text-xs"/>
                    <InputError :error="form1.errors.counter_pecok" />
                  </div>

                  <div class="flex flex-col items-start space-y-1">
                    <label class="font-bold text-xs">Wilayah Resort</label>
                    <Input v-model="form1.wilayah" required type="text" :placeholder="__('Wilayah Resort')" class="w-full text-xs"/>
                    <InputError :error="form1.errors.wilayah" />
                  </div>

                  <div class="flex flex-col items-start space-y-1">
                    <label class="font-bold text-xs">Kilometer Mesin</label>
                    <Input v-model="form1.kilometer_mesin" required type="number" step="0.01" :placeholder="__('Kilometer Mesin')" class="w-full text-xs"/>
                    <InputError :error="form1.errors.kilometer_mesin" />
                  </div>

                  <div class="flex flex-col items-start space-y-1">
                    <label class="font-bold text-xs">Daop / Divre</label>
                    
                    <div class="w-full text-xs">
                    <Select
                      v-model="form1.region_id"
                      :options="regions.map(region => ({
                        label: region.name,
                        value: region.id,
                      }))"
                      :searchable="true"
                      placeholder="Pilih Daop / Divre"
                      required
                        class="w-full border-none text-center text-xs"
                        style="font-size: 0.7rem;"
                    >
                      <template #option="{ option }">
                        <span class="text-xs antialiased">
                            {{ option.label }}
                        </span>
                      </template>
                    </Select>
                    <InputError :error="form1.errors.region_id" />
                    </div>
                  </div>
                  
                  <div class="flex flex-col items-start space-y-1">
                    <label class="font-bold text-xs">No Seri</label>
                    <Input v-model="form1.no_seri" required type="number" :placeholder="__('No Seri')" class="w-full text-xs"/>
                    <InputError :error="form1.errors.no_seri" />
                  </div>

                </div>
                
                <div class="flex justify-end mt-3 w-full text-xs">
                  <Button v-if="!report.checksheetday?.id" class="bg-green-700 hover:bg-green-900 px-4 py-1 rounded float-right mr-2 text-xs" @click.prevent="submitchecksheetday()">Simpan</Button>
                  <!-- <Button v-if="report.checksheetday?.id" class="bg-green-700 hover:bg-green-900 px-4 py-1 rounded float-right mr-2 text-xs" @click.prevent="updatechecksheetday()">Edit</Button> -->
                  <Button v-if="report.checksheetday?.id || showNextButton" class="bg-blue-700 hover:bg-blue-900 text-white px-4 py-1 rounded disabled:opacity-50 text-xs" @click="currentStep = 2" > Lanjut → </Button>
                </div>
              </div>

              <!-- Tabel Unit Komponen -->
              <div v-else-if="currentStep === 2">
                <div class="overflow-x-auto">
                  <table class="table-auto border-collapse border border-black w-full min-w-full text-[10px] md:text-xs">
                    <thead class="bg-gray-300 text-black">
                      <tr>
                        <th colspan="11" class="border border-black px-1 py-1 text-center bg-gray-600 font-bold text-white">{{ groups[currentGroupIndex] }}</th>
                      </tr>
                      <tr>
                        <th class="border border-black px-1 py-1 text-center bg-gray-200 font-bold">No</th>
                        <th class="border border-black px-1 py-1 text-center bg-gray-200 font-bold">Komponen</th>
                        <th class="border border-black px-1 py-1 text-center bg-gray-200 font-bold">Rujukan</th>
                        <th class="border border-black px-1 py-1 text-center bg-gray-200 font-bold">Cek</th>
                        <th class="border border-black px-1 py-1 text-center bg-gray-200 font-bold">Tambah</th>
                        <th class="border border-black px-1 py-1 text-center bg-gray-200 font-bold">Ganti</th>
                        <th class="border border-black px-1 py-1 text-center bg-gray-200 font-bold">Nilai Rujukan</th>
                        <th class="border border-black px-1 py-1 text-center bg-gray-200 font-bold">Kr/Dpn</th>
                        <th class="border border-black px-1 py-1 text-center bg-gray-200 font-bold">Kn/Dpn</th>
                        <th class="border border-black px-1 py-1 text-center bg-gray-200 font-bold">Sat.</th>
                        <th class="border border-black px-1 py-1 text-center bg-gray-200 font-bold">App.</th>
                      </tr>
                    </thead>

                    <tbody>
                      <tr v-for="(item, index) in currentGroupResults" :key="index">
                        <td class="border border-black px-2 py-1 text-center">{{ item.urutan }}</td>
                        <td class="border border-black px-2 py-1 text-left">{{ item.komponen }}</td>
                        <td class="border border-black px-2 py-1">{{ item.rujukan }}</td>
                        <td class="border border-black px-2 py-1 text-center">
                          <input type="checkbox" :checked="item.cek == 1" @change="toggleResult(item, 'cek')" />
                        </td>
                        <td class="border border-black px-2 py-1 text-center">
                          <input type="checkbox" :checked="item.tambahan == 1" @change="toggleResult(item, 'tambahan')"/>
                        </td>
                        <td class="border border-black px-2 py-1 text-center">
                          <input type="checkbox" :checked="item.ganti == 1" @change="toggleResult(item, 'ganti')"/>
                        </td>
                        <td class="border border-black px-2 py-1 text-center">{{ item.nilai_rujukan }}</td>
                        <td class="border border-black p-0 m-0 relative">
                          <input 
                            v-model="item.kiri_depan"
                            type="text"
                            placeholder="...."
                            class="absolute inset-0 w-full h-full border-none focus:ring-0 text-center text-[10px] p-0 m-0"
                            @change="saveTextField(item)"/>
                        </td>
                        <td class="border border-black p-0 m-0 relative">
                          <input 
                            v-model="item.kanan_depan"
                            type="text"
                            placeholder="...."
                            class="absolute inset-0 w-full h-full border-none focus:ring-0 text-center text-[10px] p-0 m-0"
                            @change="saveTextField(item)" />
                        </td>
                        <td class="border border-black px-2 py-1 text-center text-[10px] p-0 m-0">{{ item.satuan }}</td>
                        <td class="border border-black p-0 m-0 relative">
                          <input
                            v-model="item.keterangan"
                            type="text"
                            placeholder="...."
                            @change="saveTextField(item)"
                            class="absolute inset-0 w-full h-full border-none focus:ring-0 text-center text-[10px] p-0 m-0"
                          />
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <div class="flex justify-between mt-4">
                    <Button class="bg-gray-600 text-white px-4 py-1 rounded disabled:opacity-50 text-xs" @click="prevGroup"> ← Kembali </Button>

                    <Button class="bg-blue-600 text-white px-4 py-1 rounded disabled:opacity-50 text-xs" @click="nextGroup"> Lanjut → </Button>
                  </div>
                  <br>
                </div>
                </div>
                  
                <div v-else-if="currentStep === 3">
                  <table class="w-full table-auto"> 
                    <tfoot>
                      <tr class="flex flex-col lg:flex-row"> <td colspan="9" class="border border-black align-top px-2 py-2 font-semibold text-sm w-full lg:w-1/2"> 
                              Catatan riwayat gangguan :
                              <textarea
                                  v-model="form2.catatan_gangguan"
                                  class="w-full border-none text-sm resize-none mt-1 p-1"
                                  rows="5"
                                  placeholder="Tuliskan catatan riwayat gangguan di sini..."
                                  @change="saveWorkResult"
                              ></textarea>
                          </td>

                          <td colspan="4" class="border border-black align-top px-0 py-0 w-full lg:w-1/2">
                              <table class="w-full border-collapse">
                                  <thead>
                                      <tr>
                                          <th colspan="5" class="border border-black bg-gray-200 text-center font-bold py-1 text-sm">
                                              Hasil Kerja:
                                          </th>
                                      </tr>
                                      <tr class="bg-gray-100 text-center font-semibold text-xs">
                                          <th colspan="3" class="border border-black px-1 py-1 w-[70%]">Lokasi dan Jam beroperasi</th>
                                          <th class="border border-black px-1 py-1 w-[15%]">hu / hi</th>
                                          <th class="border border-black px-1 py-1 w-[15%]">Jumlah</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      <tr>
                                          <td colspan="3" class="border border-black px-1 py-0 text-left">
                                              <input
                                                  v-model="form2.lokasi_dan_jam1"
                                                  type="text"
                                                  class="w-full border-none text-xs p-0.5"
                                                  placeholder="Isi lokasi dan jam beroperasi"
                                                  @change="saveWorkResult"
                                              />
                                          </td>
                                          <td class="border border-black px-1 py-0 text-center">
                                            <select
                                                v-model="form2.hu_hi_1"
                                                class="w-full border-none text-center text-xs p-0.5"
                                                @change="saveWorkResult"
                                            >
                                                <!-- <option value="" disabled selected>Pilih</option>  -->
                                                <option value="Hulu">Hulu</option>
                                                <option value="Hilir">Hilir</option>
                                            </select>
                                          </td>
                                          <td class="border border-black px-1 py-0 text-center">
                                              <input
                                                  v-model="form2.jumlah_1"
                                                  type="text"
                                                  placeholder="...."
                                                  class="w-full border-none text-center text-xs p-0.5"
                                                  @change="saveWorkResult"
                                              />
                                          </td>
                                      </tr>
                                      <tr>
                                          <td colspan="3" class="border border-black px-1 py-0 text-left">
                                              <input
                                                  v-model="form2.lokasi_dan_jam2"
                                                  type="text"
                                                  class="w-full border-none text-xs p-0.5"
                                                  placeholder="Isi lokasi dan jam beroperasi"
                                                  @change="saveWorkResult"
                                              />
                                          </td>
                                          <td class="border border-black px-1 py-0 text-center">
                                            <select
                                                v-model="form2.hu_hi_2"
                                                class="w-full border-none text-center text-xs p-0.5"
                                                @change="saveWorkResult"
                                            >
                                                <!-- <option value="" disabled selected>Pilih</option>  -->
                                                <option value="Hulu">Hulu</option>
                                                <option value="Hilir">Hilir</option>
                                            </select>
                                          </td>
                                          <td class="border border-black px-1 py-0 text-center">
                                              <input
                                                  v-model="form2.jumlah_2"
                                                  type="text"
                                                  placeholder="...."
                                                  class="w-full border-none text-center text-xs p-0.5"
                                                  @change="saveWorkResult"
                                              />
                                          </td>
                                      </tr>
                                      <tr>
                                          <td colspan="3" class="border border-black px-1 py-0 text-left">
                                              <input
                                                  v-model="form2.lokasi_dan_jam3"
                                                  type="text"
                                                  class="w-full border-none text-xs p-0.5"
                                                  placeholder="Isi lokasi dan jam beroperasi"
                                                  @change="saveWorkResult"
                                              />
                                          </td>
                                          <td class="border border-black px-1 py-0 text-center">
                                            <select
                                                v-model="form2.hu_hi_3"
                                                class="w-full border-none text-center text-xs p-0.5"
                                                @change="saveWorkResult"
                                            >
                                                <!-- <option value="" disabled selected>Pilih</option>  -->
                                                <option value="Hulu">Hulu</option>
                                                <option value="Hilir">Hilir</option>
                                            </select>
                                          </td>
                                          <td class="border border-black px-1 py-0 text-center">
                                              <input
                                                  v-model="form2.jumlah_3" 
                                                  type="text"
                                                  placeholder="...."
                                                  class="w-full border-none text-center text-xs p-0.5"
                                                  @change="saveWorkResult"
                                              />
                                          </td>
                                      </tr>
                                      
                                      <tr class="bg-gray-200 text-center font-bold text-xs">
                                          <td colspan="2" class="border border-black py-1">Operator</td>
                                          <td class="border border-black py-1">Paraf</td>
                                          <td colspan="2" class="border border-black py-1">Validasi</td> 
                                      </tr>

                                      <tr class="bg-gray-200 text-center font-semibold text-xs">
                                          <td colspan="2" class="border border-black text-left w-64">
                                              <Select
                                                  v-model="form2.operator_by1"
                                                  :options="users.filter(user => user.id !== 1).map(user => ({
                                                      label: `[${user.username}] ${user.name.toUpperCase()}`,
                                                      value: user.id,
                                                  }))"
                                                  :searchable="true"
                                                  placeholder="Pilih"
                                                  class="w-full border-none text-center text-xs"
                                                  style="font-size: 0.7rem;"
                                                  @change="saveWorkResult"
                                              >
                                                <template #option="{ option }">
                                                  <span class="text-xs antialiased">
                                                      {{ option.label }}
                                                  </span>
                                                </template>
                                              </Select>
                                          </td>
                                          <td class="border border-black text-center text-xs py-1">
                                              Disetujui Pada : <br>
                                              {{ formatDate(localChecksheetDay.checksheetworkresult?.operator_at1) }}
                                          </td>
                                          <td colspan="2" class="border border-black text-center p-0 m-0 relative">
                                              <input
                                                  v-model="form2.validasi1"
                                                  type="text"
                                                  class="absolute inset-0 w-full h-full border-none text-center text-[10px] p-0 m-0"
                                                  placeholder="...."
                                                  @change="saveWorkResult"
                                              />
                                          </td>
                                      </tr>

                                      <tr class="bg-gray-200 text-center font-semibold text-xs">
                                          <td colspan="2" class="border border-black text-left w-64">
                                              <Select
                                                  v-model="form2.operator_by2"
                                                  :options="users.filter(user => user.id !== 1).map(user => ({
                                                      label: `[${user.username}] ${user.name.toUpperCase()}`,
                                                      value: user.id,
                                                  }))"
                                                  :searchable="true"
                                                  placeholder="Pilih"
                                                  class="w-full border-none text-center text-xs"
                                                  style="font-size: 0.7rem;"
                                                  @change="saveWorkResult"
                                              >
                                                <template #option="{ option }">
                                                  <span class="text-xs antialiased">
                                                      {{ option.label }}
                                                  </span>
                                                </template>
                                              </Select>
                                          </td>
                                          <td class="border border-black text-center text-xs py-1">
                                              Disetujui Pada : <br>
                                              {{ formatDate(localChecksheetDay.checksheetworkresult?.operator_at2) }}
                                          </td>
                                          <td colspan="2" class="border border-black text-center p-0 m-0 relative">
                                              <input
                                                  v-model="form2.validasi2"
                                                  type="text"
                                                  class="absolute inset-0 w-full h-full border-none text-center text-[10px] p-0 m-0"
                                                  placeholder="...."
                                                  @change="saveWorkResult"
                                              />
                                          </td>
                                      </tr>

                                      <tr class="bg-gray-200 text-center font-semibold text-xs">
                                          <td colspan="2" class="border border-black text-left w-64">
                                              <Select
                                                  v-model="form2.operator_by3"
                                                  :options="users.filter(user => user.id !== 1).map(user => ({
                                                      label: `[${user.username}] ${user.name.toUpperCase()}`,
                                                      value: user.id,
                                                  }))"
                                                  :searchable="true"
                                                  placeholder="Pilih"
                                                  class="w-full border-none text-center text-xs"
                                                  style="font-size: 0.7rem;"
                                                  @change="saveWorkResult"
                                              >
                                                <template #option="{ option }">
                                                  <span class="text-xs antialiased">
                                                      {{ option.label }}
                                                  </span>
                                                </template>
                                              </Select>
                                          </td>
                                          <td class="border border-black text-center text-xs py-1">
                                              Disetujui Pada : <br>
                                              {{ formatDate(localChecksheetDay.checksheetworkresult?.operator_at3) }}
                                          </td>
                                          <td colspan="2" class="border border-black text-center p-0 m-0 relative">
                                              <input
                                                  v-model="form2.validasi3"
                                                  type="text"
                                                  class="absolute inset-0 w-full h-full border-none text-center text-[10px] p-0 m-0"
                                                  placeholder="...."
                                                  @change="saveWorkResult"
                                              />
                                          </td>
                                      </tr>

                                      <tr class="bg-gray-200 text-center font-semibold text-xs">
                                          <td colspan="2" class="border border-black text-left w-64">
                                              <Select
                                                  v-model="form2.operator_by4"
                                                  :options="users.filter(user => user.id !== 1).map(user => ({
                                                      label: `[${user.username}] ${user.name.toUpperCase()}`,
                                                      value: user.id,
                                                  }))"
                                                  :searchable="true"
                                                  placeholder="Pilih"
                                                  class="w-full border-none text-center text-xs"
                                                  style="font-size: 0.7rem;"
                                                  @change="saveWorkResult"
                                              >
                                                <template #option="{ option }">
                                                  <span class="text-xs antialiased">
                                                      {{ option.label }}
                                                  </span>
                                                </template>
                                              </Select>
                                          </td>
                                          <td class="border border-black text-center text-xs py-1">
                                              Disetujui Pada : <br>
                                              {{ formatDate(localChecksheetDay.checksheetworkresult?.operator_at4) }}
                                          </td>
                                          <td colspan="2" class="border border-black text-center p-0 m-0 relative">
                                              <input
                                                  v-model="form2.validasi4"
                                                  type="text"
                                                  class="absolute inset-0 w-full h-full border-none text-center text-[10px] p-0 m-0"
                                                  placeholder="...."
                                                  @change="saveWorkResult"
                                              />
                                          </td>
                                      </tr>

                                  </tbody>
                              </table>
                          </td>
                      </tr>
                    </tfoot>
                  </table>
                  
                  <div class="d-flex justify-content-end mt-3">
                      <Button v-if="!report.checksheetday?.checksheetworkresult?.id" class="bg-green-700 hover:bg-green-900 px-4 py-1 rounded float-right mr-2 text-xs" @click.prevent="submitchecksheetworkresult()">Simpan</Button>
                      <!-- <Button v-if="report.checksheetday?.checksheetworkresult?.id" class="bg-blue-700 hover:bg-blue-900 px-4 py-1 rounded float-right mr-2 text-xs" @click.prevent="updatechecksheetworkresult()">Edit</Button> -->
                      <Button v-if="canApprove" class="bg-blue-700 hover:bg-blue-900 float-right mr-2 text-xs" @click.prevent="approvechecksheetworkresult()">Approve</Button>
                      <Button v-if="canChangeMode" class="bg-orange-600 hover:bg-orange-800 float-right mr-2 text-xs" @click.prevent="setMode('working')">Mode Working >></Button>
                      <Button v-if="canChangeMode" class="bg-red-600 hover:bg-red-800 float-right mr-2 text-xs" @click.prevent="setMode('warmingup')"> Mode Warming Up >></Button>
                      <Button v-if="report.checksheetday?.id || currentStep === 2" class="bg-gray-700 hover:bg-gray-900 px-4 py-1 rounded text-xs " @click="currentStep = 2" > ← Kembali</Button>
                  </div>

              </div>
						</div>
            <!-- section checksheetday -->

            <!-- section warmingup -->
            <div v-if="currentSection === 'warmingup'" class="tab-pane fade" id="list-warmingup" role="tabpanel" aria-labelledby="list-warmingup-list">
							<div class="grid grid-cols-1 gap-4 mb-4 md:grid-cols-2">

                <div class="flex flex-col items-start space-y-1">
                  <label for="machine_id" class="font-bold text-xs">
                    {{ __('Nama Mesin') }}
                  </label>
                  
                  <div class="w-full">
                    <Select
                      v-model="form.machine_id"
                      :options="machines.map(machine => ({
                        label: `${machine.name} - ${machine.type} - ${machine.nomor} - ${machine.no_sarana} (${machine.region.name})`,
                        value: machine.id,
                      }))"
                      :searchable="true"
                      placeholder="Pilih Mesin"
                      class="w-full border-none text-center text-xs"
                      style="font-size: 0.7rem;"
                      disabled
                    >
                      <template #option="{ option }">
                        <span class="text-xs antialiased">
                            {{ option.label }}
                        </span>
                      </template>
                    </Select>
                    <InputError :error="form.errors.machine_id" />
                  </div>
                </div>
                
                <!-- <div class="flex flex-col items-start space-y-1">
                  <label for="tanggal" class="font-bold text-xs">
                    {{ __('Tanggal') }}
                  </label>
                  
                  <Input
                    v-model="form3.tanggal"
                    :placeholder="__('Tanggal')"
                    type="datetime-local"
                    class="text-xs"
                    required
                  />     

                  <InputError :error="form3.errors.tanggal"/>
                </div>

                <div class="flex flex-col items-start space-y-1">
                  <label for="cuaca" class="font-bold text-xs">
                    {{ __('Cuaca') }}
                  </label>
                  
                  <Input
                    v-model="form3.cuaca"
                    :placeholder="__('Cuaca')"
                    type="text"
                    class="text-xs"
                    required
                  />     

                  <InputError :error="form3.errors.cuaca"/>
                </div>

                <div class="flex flex-col items-start space-y-1">
                  <label for="jenis_kpjr" class="font-bold text-xs">
                    {{ __('Jenis KPJR') }}
                  </label>
                  
                  <Input
                    v-model="form3.jenis_kpjr"
                    :placeholder="__('Jenis KPJR')"
                    type="text"
                    class="text-xs"
                    required
                  />     

                  <InputError :error="form3.errors.jenis_kpjr"/>
                </div>

                <div class="flex flex-col items-start space-y-1">
                  <label for="nomor_mesin" class="font-bold text-xs">
                    {{ __('Nomor Mesin') }}
                  </label>
                  
                  <Input
                    v-model="form3.nomor_mesin"
                    :placeholder="__('Nomor Mesin')"
                    type="text"
                    class="text-xs"
                    required
                  />     

                  <InputError :error="form3.errors.nomor_mesin"/>
                </div>

                <div class="flex flex-col items-start space-y-1">
                  <label for="nomor_sarana" class="font-bold text-xs">
                    {{ __('Nomor Sarana') }}
                  </label>
                  
                  <Input
                    v-model="form3.nomor_sarana"
                    :placeholder="__('Nomor Sarana')"
                    type="text"
                    class="text-xs"
                    required
                  />     

                  <InputError :error="form3.errors.nomor_sarana"/>
                </div>

                <div class="flex flex-col items-start space-y-1">
                  <label for="waktu_start_engine" class="font-bold text-xs">
                    {{ __('Waktu Start Engine') }}
                  </label>
                  
                  <Input
                    v-model="form3.waktu_start_engine"
                    :placeholder="__('Waktu Start Engine')"
                    type="time"
                    class="text-xs"
                    required
                  />

                  <InputError :error="form3.errors.waktu_start_engine"/>
                </div>

                <div class="flex flex-col items-start space-y-1">
                  <label for="jam_traveling_awal" class="font-bold text-xs">
                    {{ __('Jam Traveling Awal') }}
                  </label>
                  
                  <Input
                    v-model="form3.jam_traveling_awal"
                    :placeholder="__('Jam Traveling Awal')"
                    type="text"
                    class="text-xs"
                    required
                  />     

                  <InputError :error="form3.errors.jam_traveling_awal"/>
                </div>
              
                <div class="flex flex-col items-start space-y-1">
                  <label for="jam_kerja_awal" class="font-bold text-xs">
                    {{ __('Jam Kerja Awal') }}
                  </label>
                  
                  <Input
                    v-model="form3.jam_kerja_awal"
                    :placeholder="__('Jam Kerja Awal')"
                    type="time"
                    class="text-xs"
                    required
                  />

                  <InputError :error="form3.errors.jam_kerja_awal"/>
                </div>
              
                <div class="flex flex-col items-start space-y-1">
                  <label for="jam_mesin_awal" class="font-bold text-xs">
                    {{ __('Jam Mesin Awal') }}
                  </label>
                  
                  <Input
                    v-model="form3.jam_mesin_awal"
                    :placeholder="__('Jam Mesin Awal')"
                    type="text"
                    class="text-xs"
                    required
                  />

                  <InputError :error="form3.errors.jam_mesin_awal"/>
                </div>

                <div class="flex flex-col items-start space-y-1">
                  <label for="jam_generator_awal" class="font-bold text-xs">
                    {{ __('Jam Generator Awal') }}
                  </label>
                  
                  <Input
                    v-model="form3.jam_generator_awal"
                    :placeholder="__('Jam Generator Awal')"
                    type="text"
                    class="text-xs"
                    required
                  />

                  <InputError :error="form3.errors.jam_generator_awal"/>
                </div>

                <div class="flex flex-col items-start space-y-1">
                  <label for="counter_tamping_awal" class="font-bold text-xs">
                    {{ __('Counter Tamping Awal') }}
                  </label>

                  <Input
                    v-model="form3.counter_tamping_awal"
                    :placeholder="__('Counter Tamping Awal')"
                    type="number"
                    class="text-xs"
                    required
                  />

                  <InputError :error="form3.errors.counter_tamping_awal"/>
                </div>

                <div class="flex flex-col items-start space-y-1">
                  <label for="oddometer_awal" class="font-bold text-xs">
                    {{ __('Oddometer Awal') }}
                  </label>
                  
                  <Input
                    v-model="form3.oddometer_awal"
                    :placeholder="__('Oddometer Awal')"
                    type="number"
                    class="text-xs"
                    required
                  />

                  <InputError :error="form3.errors.oddometer_awal"/>
                </div>

                <div class="flex flex-col items-start space-y-1">
                  <label for="hsd_awal_kerja" class="font-bold text-xs">
                    {{ __('HSD Awal Kerja') }}
                  </label>
                  
                  <Input
                    v-model="form3.hsd_awal_kerja"
                    :placeholder="__('HSD Awal Kerja')"
                    type="number"
                    class="text-xs"
                    required
                  />

                  <InputError :error="form3.errors.hsd_awal_kerja"/>
                </div>                 -->

                <div class="flex flex-col items-start space-y-1">
                  <label for="konsumsi_hsd" class="font-bold text-xs">
                    {{ __('Konsumsi HSD') }}
                  </label>
                  
                  <Input
                    v-model="form3.konsumsi_hsd"
                    :placeholder="__('Konsumsi HSD')"
                    type="number"
                    class="text-xs"
                    required
                  />

                  <InputError :error="form3.errors.konsumsi_hsd"/>
                </div>

                <div class="flex flex-col items-start space-y-1">
                  <label for="waktu_stop_engine" class="font-bold text-xs">
                    {{ __('Waktu Stop Engine') }}
                  </label>
                  
                  <Input
                    v-model="form3.waktu_stop_engine"
                    :placeholder="__('Waktu Stop Engine')"
                    type="time"
                    class="text-xs"
                    required
                  />

                  <InputError :error="form3.errors.waktu_stop_engine"/>
                </div>

                <div class="flex flex-col items-start space-y-1">
                  <label for="jam_traveling_akhir" class="font-bold text-xs">
                    {{ __('Jam Traveling Akhir') }}
                  </label>
                  
                  <Input
                    v-model="form3.jam_traveling_akhir"
                    :placeholder="__('Jam Traveling Akhir')"
                    type="text"
                    class="text-xs"
                    required
                  />     

                  <InputError :error="form3.errors.jam_traveling_akhir"/>
                </div>
              
                <div class="flex flex-col items-start space-y-1">
                  <label for="jam_kerja_akhir" class="font-bold text-xs">
                    {{ __('Jam Kerja Akhir') }}
                  </label>
                  
                  <Input
                    v-model="form3.jam_kerja_akhir"
                    :placeholder="__('Jam Kerja Akhir')"
                    type="time"
                    class="text-xs"
                    required
                  />

                  <InputError :error="form3.errors.jam_kerja_akhir"/>
                </div>
              
                <div class="flex flex-col items-start space-y-1">
                  <label for="jam_mesin_akhir" class="font-bold text-xs">
                    {{ __('Jam Mesin Akhir') }}
                  </label>
                  
                  <Input
                    v-model="form3.jam_mesin_akhir"
                    :placeholder="__('Jam Mesin Akhir')"
                    type="text"
                    class="text-xs"
                    required
                  />

                  <InputError :error="form3.errors.jam_mesin_akhir"/>
                </div>

                <div class="flex flex-col items-start space-y-1">
                  <label for="jam_generator_akhir" class="font-bold text-xs">
                    {{ __('Jam Generator Akhir') }}
                  </label>
                  
                  <Input
                    v-model="form3.jam_generator_akhir"
                    :placeholder="__('Jam Generator Akhir')"
                    type="text"
                    class="text-xs"
                    required
                  />

                  <InputError :error="form3.errors.jam_generator_akhir"/>
                </div>

                <div class="flex flex-col items-start space-y-1">
                  <label for="counter_tamping_akhir" class="font-bold text-xs">
                    {{ __('Counter Tamping Akhir') }}
                  </label>

                  <Input
                    v-model="form3.counter_tamping_akhir"
                    :placeholder="__('Counter Tamping Akhir')"
                    type="number"
                    class="text-xs"
                    required
                  />

                  <InputError :error="form3.errors.counter_tamping_akhir"/>
                </div>

                <div class="flex flex-col items-start space-y-1">
                  <label for="oddometer_akhir" class="font-bold text-xs">
                    {{ __('Oddometer Akhir') }}
                  </label>
                  
                  <Input
                    v-model="form3.oddometer_akhir"
                    :placeholder="__('Oddometer Akhir')"
                    type="number"
                    class="text-xs"
                    required
                  />

                  <InputError :error="form3.errors.oddometer_akhir"/>
                </div>

                <div class="flex flex-col items-start space-y-1">
                  <label for="hsd_akhir_kerja" class="font-bold text-xs">
                    {{ __('HSD Akhir Kerja') }}
                  </label>
                  
                  <Input
                    v-model="form3.hsd_akhir_kerja"
                    :placeholder="__('HSD Akhir Kerja')"
                    type="number"
                    class="text-xs"
                    required
                  />

                  <InputError :error="form3.errors.hsd_akhir_kerja"/>
                </div>    
                
                <!-- <div class="flex flex-col items-start space-y-1">
                  <label for="operator_by1" class="font-bold text-xs">
                    {{ __('Operator 1') }}
                  </label>
                  
                  <Select
                    v-model="form3.operator_by1"
                    :options="users.filter(user => user.id !== 1 && user.id !== 3).map(user => ({
                      label: `${user.name} - ${user.username}`,
                      value: user.id,
                    }))"
                    :searchable="true"
                    placeholder="Pilih Operator 1"
                    class="w-full border-none text-center text-xs"
                    style="font-size: 0.7rem;"
                  >
                    <template #option="{ option }">
                      <span class="text-xs antialiased">
                          {{ option.label }}
                      </span>
                    </template>
                  </Select>

                  <InputError :error="form3.errors.operator_by1"/>
                </div>      
                
                <div class="flex flex-col items-start space-y-1">
                  <label for="operator_by2" class="font-bold text-xs">
                    {{ __('Operator 2') }}
                  </label>
                  
                  <Select
                    v-model="form3.operator_by2"
                    :options="users.filter(user => user.id !== 1 && user.id !== 3).map(user => ({
                      label: `${user.name} - ${user.username}`,
                      value: user.id,
                    }))"
                    :searchable="true"
                    placeholder="Pilih Operator 2"
                    class="w-full border-none text-center text-xs"
                    style="font-size: 0.7rem;"
                  >
                    <template #option="{ option }">
                      <span class="text-xs antialiased">
                          {{ option.label }}
                      </span>
                    </template>
                  </Select>

                  <InputError :error="form3.errors.operator_by2"/>
                </div>   
                
                <div class="flex flex-col items-start space-y-1">
                  <label for="operator_by3" class="font-bold text-xs">
                    {{ __('Operator 3') }}
                  </label>
                  
                  <Select
                    v-model="form3.operator_by3"
                    :options="users.filter(user => user.id !== 1 && user.id !== 3).map(user => ({
                      label: `${user.name} - ${user.username}`,
                      value: user.id,
                    }))"
                    :searchable="true"
                    placeholder="Pilih Operator 3"
                    class="w-full border-none text-center text-xs"
                    style="font-size: 0.7rem;"
                  >
                    <template #option="{ option }">
                      <span class="text-xs antialiased">
                          {{ option.label }}
                      </span>
                    </template>
                  </Select>

                  <InputError :error="form3.errors.operator_by3"/>
                </div>   
                
                <div class="flex flex-col items-start space-y-1">
                  <label for="approved_by" class="font-bold text-xs">
                    {{ __('Pengawal 1') }}
                  </label>
                  
                  <Select
                    v-model="form3.approved_by"
                    :options="users.filter(user => user.id !== 1 && user.id !== 3).map(user => ({
                      label: `${user.name} - ${user.username}`,
                      value: user.id,
                    }))"
                    :searchable="true"
                    placeholder="Pilih Pengawal 1"
                    class="w-full border-none text-center text-xs"
                    style="font-size: 0.7rem;"
                  >
                    <template #option="{ option }">
                      <span class="text-xs antialiased">
                          {{ option.label }}
                      </span>
                    </template>
                  </Select>

                  <InputError :error="form3.errors.approved_by"/>
                </div>  
                
                <div class="flex flex-col items-start space-y-1">
                  <label for="approved_by1" class="font-bold text-xs">
                    {{ __('Pengawal 2') }}
                  </label>
                  
                  <Select
                    v-model="form3.approved_by1"
                    :options="users.filter(user => user.id !== 1 && user.id !== 3).map(user => ({
                      label: `${user.name} - ${user.username}`,
                      value: user.id,
                    }))"
                    :searchable="true"
                    placeholder="Pilih Pengawal 2"
                    class="w-full border-none text-center text-xs"
                    style="font-size: 0.7rem;"
                  >
                    <template #option="{ option }">
                      <span class="text-xs antialiased">
                          {{ option.label }}
                      </span>
                    </template>
                  </Select>

                  <InputError :error="form3.errors.approved_by1"/>
                </div> -->

                <!-- <div class="flex flex-col items-start space-y-1">
                  <label for="note" class="font-bold text-xs">
                    {{ __('Keterangan') }}
                  </label>
                  
                  <TextArea
                    v-model="form3.note"
                    :placeholder="__('Keterangan')"
                    type="text"
                    class="text-xs"
                  />
                  <InputError :error="form3.errors.note"/>
                </div> -->
              </div>

              <p v-if="warmingup?.operator_at1" class="text-xs font-bold text-blue-600">Operator 1 telah menyetujui pada tanggal : {{ formatDate(warmingup?.operator_at1)}}</p>
              <p v-if="warmingup?.operator_at2" class="text-xs font-bold text-blue-600">Operator 2 telah menyetujui pada tanggal : {{ formatDate(warmingup?.operator_at2)}}</p>
              <p v-if="warmingup?.operator_at3" class="text-xs font-bold text-blue-600">Operator 3 telah menyetujui pada tanggal : {{ formatDate(warmingup?.operator_at3)}}</p>
							
							<div class="d-flex justify-content-end mt-3">
								<Button v-if="!report.warmingup?.id" class="bg-green-700 hover:bg-green-900 px-4 py-1 rounded float-right mr-2 text-xs" @click.prevent="submitwarmingup()">Simpan</Button>
                <!-- <Button v-else class="bg-blue-700 hover:bg-blue-900 px-4 py-1 rounded float-right mr-2 text-xs" @click.prevent="updatewarmingup()">Edit</Button> -->
                <Button v-if="canApproveWarmingUp && !warmingup?.operator_at1" class="bg-blue-700 hover:bg-blue-900 float-right mr-2 text-xs" @click.prevent="approvewarmingup(1)">Approve 1</Button>
                <Button v-if="canApproveWarmingUp && warmingup?.operator_at1 && !warmingup?.operator_at2" class="bg-blue-700 hover:bg-blue-900 float-right mr-2 text-xs" @click.prevent="approvewarmingup(2)">Approve 2</Button>
                <Button v-if="canApproveWarmingUp && warmingup?.operator_at1 && warmingup?.operator_at2 && !warmingup?.operator_at3" class="bg-blue-700 hover:bg-blue-900 float-right mr-2 text-xs" @click.prevent="approvewarmingup(3)">Approve 3</Button>

							</div>
						</div>
            <!-- section warmingup -->

            <!-- section upload -->             
					  <div v-if="currentSection === 'upload'" class="tab-pane fade show active" id="list-upload" role="tabpanel" aria-labelledby="list-upload-list">

              <div class="flex flex-col space-y-4 p-1">

                <div class="flex flex-col items-start space-y-1">
                    <label for="date" class="font-bold text-xs">
                      {{ __('Tanggal') }}
                    </label>
                    
                      <div class="w-full">
                        <Input
                          v-model="form5.date"
                          :placeholder="__('Tanggal')"
                          type="date"
                          class="text-xs"
                          @change="autosaveUpload"
                        />
                  <InputError :error="form.errors.date"/>
                  </div>
                </div>

                <div class="flex flex-col items-start space-y-1">
                  <AttachmentInline 
                    :model="upload ?? {}"  
                    type="Upload" 
                    :redaction="`Lampiran Upload Foto Silang`" 
                  />
                </div>

              </div>
            </div>
            <!-- section upload -->

            <!-- section workresult -->
            <div v-if="currentSection === 'workresult'" class="tab-pane fade" id="list-workresult" role="tabpanel" aria-labelledby="list-warmingup-list">

              <div v-if="currentStep1 === 1 " class="grid grid-cols-1 gap-1 mb-1 md:grid-cols-1">
                <div class="font-bold rounded-md text-xs">A. DATA PEKERJAAN</div><br>
                <div  class="grid grid-cols-1 gap-4 mb-1 md:grid-cols-1">
                  <div class="flex flex-col items-start space-y-1">
                    <label for="wilayah" class="font-bold text-xs">
                      {{ __('Wilayah resor') }}
                    </label>
                    
                      <Input
                        v-model="form4.wilayah"
                        :placeholder="__('Wilayah resor')"
                        type="text"
                        class="w-full text-xs"
                      />
                        
                    <InputError :error="form4.errors.wilayah" />
                  </div>

                  <div class="flex flex-col items-start space-y-1">
                    <label for="petak_jalan" class="font-bold text-xs">
                      {{ __('Petak Jalan') }}
                    </label>
                    
                      <Input
                        v-model="form4.petak_jalan"
                        :placeholder="__('Petak Jalan')"
                        type="text"
                        class="w-full text-xs"
                      />

                    <InputError :error="form4.errors.petak_jalan" />
                  </div>

                  <div class="flex flex-col items-start space-y-1">
                    <label for="lokasi_stabling_awal" class="font-bold text-xs">
                      {{ __('Lokasi Stabling Awal') }}
                    </label>
                    
                      <Input
                        v-model="form4.lokasi_stabling_awal"
                        :placeholder="__('Lokasi Stabling Awal')"
                        type="text"
                        class="w-full text-xs"
                      />
                    <InputError :error="form4.errors.lokasi_stabling_awal" />
                  </div>

                  <div class="flex flex-col items-start space-y-1">
                    <label for="lokasi_stabling_akhir" class="font-bold text-xs">
                      {{ __('Lokasi Stabling Akhir') }}
                    </label>
                    
                      <Input
                        v-model="form4.lokasi_stabling_akhir"
                        :placeholder="__('Lokasi Stabling Akhir')"
                        type="text"
                        class="w-full text-xs"
                      />
                    <InputError :error="form4.errors.lokasi_stabling_akhir" />
                  </div>

                  <div class="flex flex-col items-start space-y-1">
                    <label for="kelas_jalan" class="font-bold text-xs">
                      {{ __('Kelas Jalan') }}
                    </label>
                    
                      <Input
                        v-model="form4.kelas_jalan"
                        :placeholder="__('Kelas Jalan')"
                        type="text"
                        class="w-full text-xs"
                      />

                    <InputError :error="form4.errors.kelas_jalan" />
                  </div>
                </div>
                
                <div class="space-y-2">
                  <!-- Baris 1 -->
                  <div class="flex flex-col items-start space-y-1">
                    <label class="md:col-span-2 text-xs font-bold">Lokasi (Km/Hm)</label>
                    <div class="md:col-span-6 flex flex-wrap items-center gap-1 w-full"> 
                      <div class="flex items-center gap-1 flex-wrap flex-grow">
                        <Input v-model="form4.lokasi_awal1" :placeholder="__('Km/Hm')" type="text" class="dot-input flex-1 min-w-[120px] text-xs" /> 
                        <span class="text-center text-xs">s/d</span>
                        <Input v-model="form4.lokasi_akhir1" :placeholder="__('Km/Hm')" type="text" class="dot-input flex-1 min-w-[120px] text-xs" /> 
                        <!-- <span class=" text-xs">(Hu/Hi)</span> -->
                         <select 
                            v-model="form4.hu_hi1"
                            class="border border-gray-300 rounded-md px-2 py-1 h-9 text-xs bg-white focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                          >
                            <option value="" disabled>Pilih</option>
                            <option value="Hu/Hi">Hulu/Hiilir</option>
                            <option value="Tunggal">Tunggal</option>
                          </select>
                      </div>
                      
                      <div class="flex items-center gap-1 ml-auto flex-shrink-0">
                        <span class="whitespace-nowrap text-xs text-center font-bold">Jumlah (Km/Hm)</span>
                        <span>:</span>
                        <Input v-model="form4.jumlah1" :placeholder="__('Jumlah Km/Hm')" type="text" class="dot-input w-28 text-right text-xs" /> 
                        <span class="text-xs">M'sp</span>
                      </div>
                    </div>
                  </div>

                  <!-- Baris 2 -->
                  <div class="flex flex-col items-start space-y-1">
                    <!-- <label class="md:col-span-2 text-xs invisible">Lokasi (Km/Hm)</label> -->
                    <div class="md:col-span-6 flex flex-wrap items-center gap-1 w-full"> 
                      <div class="flex items-center gap-1 flex-wrap flex-grow">
                        <Input v-model="form4.lokasi_awal2" :placeholder="__('Km/Hm')" type="text" class="dot-input flex-1 min-w-[120px] text-xs" /> 
                        <span class="text-center text-xs">s/d</span>
                        <Input v-model="form4.lokasi_akhir2" :placeholder="__('Km/Hm')" type="text" class="dot-input flex-1 min-w-[120px] text-xs" />
                         <select 
                            v-model="form4.hu_hi2"
                            class="border border-gray-300 rounded-md px-2 py-1 h-9 text-xs bg-white focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                          >
                            <option value="" disabled>Pilih</option>
                            <option value="Hu/Hi">Hulu/Hiilir</option>
                            <option value="Tunggal">Tunggal</option>
                          </select>
                      </div>
                      
                      <div class="flex items-center gap-1 ml-auto flex-shrink-0">
                        <span class="whitespace-nowrap text-xs text-center font-bold">Jumlah (Km/Hm)</span>
                        <span>:</span>
                        <Input v-model="form4.jumlah2" :placeholder="__('Jumlah Km/Hm')" type="text" class="dot-input w-28 text-right text-xs" /> 
                        <span class="text-xs">M'sp</span>
                      </div>
                    </div>
                  </div>

                  <!-- Baris 3 -->
                  <div class="flex flex-col items-start space-y-1">
                    <!-- <label class="md:col-span-2 text-xs invisible">Lokasi (Km/Hm)</label> -->
                    <div class="md:col-span-6 flex flex-wrap items-center gap-1 w-full"> 
                      <div class="flex items-center gap-1 flex-wrap flex-grow">
                        <Input v-model="form4.lokasi_awal3" :placeholder="__('Km/Hm')" type="text" class="dot-input flex-1 min-w-[120px] text-xs" /> 
                        <span class="text-center text-xs">s/d</span>
                        <Input v-model="form4.lokasi_akhir3" :placeholder="__('Km/Hm')" type="text" class="dot-input flex-1 min-w-[120px] text-xs" />
                         <select 
                            v-model="form4.hu_hi3"
                            class="border border-gray-300 rounded-md px-2 py-1 h-9 text-xs bg-white focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                          >
                            <option value="" disabled>Pilih</option>
                            <option value="Hu/Hi">Hulu/Hiilir</option>
                            <option value="Tunggal">Tunggal</option>
                          </select>
                      </div>
                      
                      <div class="flex items-center gap-1 ml-auto flex-shrink-0">
                        <span class="whitespace-nowrap text-xs text-center font-bold">Jumlah (Km/Hm)</span>
                        <span>:</span>
                        <Input v-model="form4.jumlah3" :placeholder="__('Jumlah Km/Hm')" type="text" class="dot-input w-28 text-right text-xs" /> 
                        <span class="text-xs">M'sp</span>
                      </div>
                    </div>
                  </div>

                  <!-- Baris Total -->
                  <div class="flex flex-col items-start space-y-1">
                    <!-- <label class="md:col-span-2 text-xs invisible">Lokasi (Km/Hm)</label> -->
                    <div class="md:col-span-6 flex flex-wrap items-center gap-1 w-full"> 
                      <div class="flex items-center gap-1 flex-wrap flex-grow">
                      </div>
                      
                      <div class="flex items-center gap-1 ml-auto flex-shrink-0">
                        <span class="whitespace-nowrap text-xs text-center font-bold">Total (Km/Hm)</span>
                        <span>:</span>
                        <Input v-model="form4.total_distance" readonly :placeholder="__('Total Km/Hm')" type="text" class="dot-input w-28 text-right text-xs" /> 
                        <span class="text-xs">M'sp</span>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="space-y-2">
                  <!-- Baris 1 -->
                  <div class="flex flex-col items-start space-y-1">
                    <label class="md:col-span-2 text-xs font-bold">No Wesel</label>
                    <div class="md:col-span-6 flex flex-wrap items-center gap-1 w-full"> 
                      <div class="flex items-center gap-1 flex-wrap flex-grow">
                        <Input v-model="form4.no_wesel1" :placeholder="__('Km/Hm')" type="text" class="dot-input flex-1 min-w-[120px] text-xs" /> 
                        <span class="text-center text-xs">Km/Hm : </span>
                        <Input v-model="form4.km_hm1" :placeholder="__('Km/Hm')" type="text" class="dot-input flex-1 min-w-[120px] text-xs" /> 
                         <select
                            v-model="form4.hu_hi4"
                            class="border border-gray-300 rounded-md px-2 py-1 h-9 text-xs bg-white focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                          >
                            <option value="" disabled>Pilih</option>
                            <option value="Hu/Hi">Hulu/Hiilir</option>
                            <option value="Tunggal">Tunggal</option>
                          </select>
                      </div>
                      
                      <div class="flex items-center gap-1 ml-auto flex-shrink-0">
                        <span class="whitespace-nowrap text-xs text-center font-bold">Jumlah Wesel</span>
                        <span>:</span>
                        <Input v-model="form4.jumlah_wesel1" :placeholder="__('Jumlah Wesel')" type="text" class="dot-input w-28 text-right text-xs" /> 
                        <span class="text-xs">Unit</span>
                      </div>
                    </div>
                  </div>

                  <!-- Baris 2 -->
                  <div class="flex flex-col items-start space-y-1">
                    <div class="md:col-span-6 flex flex-wrap items-center gap-1 w-full"> 
                      <div class="flex items-center gap-1 flex-wrap flex-grow">
                        <Input v-model="form4.no_wesel2" :placeholder="__('Km/Hm')" type="text" class="dot-input flex-1 min-w-[120px] text-xs" /> 
                        <span class="text-center text-xs">Km/Hm : </span>
                        <Input v-model="form4.km_hm2" :placeholder="__('Km/Hm')" type="text" class="dot-input flex-1 min-w-[120px] text-xs" /> 
                         <select 
                            v-model="form4.hu_hi5"
                            class="border border-gray-300 rounded-md px-2 py-1 h-9 text-xs bg-white focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                          >
                            <option value="" disabled>Pilih</option>
                            <option value="Hu/Hi">Hulu/Hiilir</option>
                            <option value="Tunggal">Tunggal</option>
                          </select>
                      </div>
                      
                      <div class="flex items-center gap-1 ml-auto flex-shrink-0">
                        <span class="whitespace-nowrap text-xs text-center font-bold">Jumlah Wesel</span>
                        <span>:</span>
                        <Input v-model="form4.jumlah_wesel2" :placeholder="__('Jumlah Wesel')" type="text" class="dot-input w-28 text-right text-xs" /> 
                        <span class="text-xs">Unit</span>
                      </div>
                    </div>
                  </div>

                  <!-- Baris 3 -->
                  <div class="flex flex-col items-start space-y-1">
                    <!-- <label class="md:col-span-2 text-xs invisible">Lokasi (Km/Hm)</label> -->
                    <div class="md:col-span-6 flex flex-wrap items-center gap-1 w-full"> 
                      <div class="flex items-center gap-1 flex-wrap flex-grow">
                        <Input v-model="form4.no_wesel3" :placeholder="__('Km/Hm')" type="text" class="dot-input flex-1 min-w-[120px] text-xs" /> 
                        <span class="text-center text-xs">Km/Hm : </span>
                        <Input v-model="form4.km_hm3" :placeholder="__('Km/Hm')" type="text" class="dot-input flex-1 min-w-[120px] text-xs" /> 
                         <select 
                            v-model="form4.hu_hi6"
                            class="border border-gray-300 rounded-md px-2 py-1 h-9 text-xs bg-white focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                          >
                            <option value="" disabled>Pilih</option>
                            <option value="Hu/Hi">Hulu/Hiilir</option>
                            <option value="Tunggal">Tunggal</option>
                          </select>
                      </div>
                      
                      <div class="flex items-center gap-1 ml-auto flex-shrink-0">
                        <span class="whitespace-nowrap text-xs text-center font-bold">Jumlah Wesel</span>
                        <span>:</span>
                        <Input v-model="form4.jumlah_wesel3" :placeholder="__('Jumlah Wesel')" type="text" class="dot-input w-28 text-right text-xs" /> 
                        <span class="text-xs">Unit</span>
                      </div>
                    </div>
                  </div>

                  <!-- Baris Total -->
                  <div class="flex flex-col items-start space-y-1">
                    <!-- <label class="md:col-span-2 text-xs invisible">Lokasi (Km/Hm)</label> -->
                    <div class="md:col-span-6 flex flex-wrap items-center gap-1 w-full"> 
                      <div class="flex items-center gap-1 flex-wrap flex-grow">
                      </div>
                      
                      <div class="flex items-center gap-1 ml-auto flex-shrink-0">
                        <span class="whitespace-nowrap text-xs text-center font-bold">Total Wesel</span>
                        <span>:</span>
                        <Input v-model="form4.total_wesel" readonly :placeholder="__('Total Wesel')" type="text" class="dot-input w-28 text-right text-xs" /> 
                        <span class="text-xs">Unit</span>
                      </div>
                    </div>
                  </div>
                </div>

                <div v-if="currentStep1 === 1" class="flex justify-end mt-4 w-full text-xs"> 
                    <Button class="bg-blue-700 hover:bg-blue-900 text-white px-4 py-1 rounded disabled:opacity-50 text-xs" @click="currentStep1 = 2" > Lanjut → </Button>
                </div>
              </div>

              <div v-if="currentStep1 === 2 " class="grid grid-cols-1 gap-4 mb-2 md:grid-cols-2">
                <div class="font-bold rounded-md text-xs">B. DATA OPERASI MESIN</div><br>

                <!-- <div class="grid grid-cols-2 gap-1 mb-4 text-sm"> -->
                  
                  <div class="flex flex-col items-start space-y-1">
                    <label for="waktu_stop_engine" class="font-bold text-xs">
                      {{ __('Waktu Stop Engine') }}
                    </label>
                    
                    <Input
                      v-model="form4.waktu_stop_engine"
                      :placeholder="__('Waktu Stop Engine')"
                      type="time"
                      class="w-full text-xs"
                    />

                    <InputError :error="form4.errors.waktu_stop_engine" />
                  </div>

                  <div class="flex flex-col items-start space-y-1">
                    <label for="jam_traveling_akhir" class="font-bold text-xs">
                      {{ __('Jam Travelling Akhir') }}
                    </label>
                    
                    <Input
                      v-model="form4.jam_traveling_akhir"
                      :placeholder="__('Jam Travelling Akhir')"
                      type="text"
                      class="w-full text-xs"
                    />

                    <InputError :error="form4.errors.jam_traveling_akhir" />
                  </div>

                  <div class="flex flex-col items-start space-y-1">
                    <label for="jam_kerja_akhir" class="font-bold text-xs">
                      {{ __('Jam Kerja Akhir') }}
                    </label>
                    
                    <Input
                      v-model="form4.jam_kerja_akhir"
                      :placeholder="__('Jam Kerja Akhir')"
                      type="time"
                      class="w-full text-xs"
                    />

                    <InputError :error="form4.errors.jam_kerja_akhir" />
                  </div>

                  <div class="flex flex-col items-start space-y-1">
                    <label for="jam_mesin_akhir" class="font-bold text-xs">
                      {{ __('Jam Mesin Akhir') }}
                    </label>
                    
                    <Input
                      v-model="form4.jam_mesin_akhir"
                      :placeholder="__('Jam Mesin Akhir')"
                      type="text"
                      class="w-full text-xs"
                    />

                    <InputError :error="form4.errors.jam_mesin_akhir" />
                  </div>

                  <div class="flex flex-col items-start space-y-1">
                    <label for="jam_generator_akhir" class="font-bold text-xs">
                      {{ __('Jam Generator Akhir') }}
                    </label>
                    
                    <Input
                      v-model="form4.jam_generator_akhir"
                      :placeholder="__('Jam Generator Akhir')"
                      type="text"
                      class="w-full text-xs"
                    />

                    <InputError :error="form4.errors.jam_generator_akhir" />
                  </div>
                  
                  <div class="flex flex-col items-start space-y-1">
                    <label for="counter_tamping_akhir" class="font-bold text-xs">
                      {{ __('Counter Tamping Akhir') }}
                    </label>
                    
                    <Input
                      v-model="form4.counter_tamping_akhir"
                      :placeholder="__('Counter Tamping Akhir')"
                      type="number"
                      class="w-full text-xs"
                    />
                        
                    <InputError :error="form4.errors.counter_tamping_akhir" />
                  </div>
                  
                  <div class="flex flex-col items-start space-y-1">
                    <label for="oddometer_akhir" class="font-bold text-xs">
                      {{ __('Oddometer Akhir') }}
                    </label>
                    
                    <Input
                      v-model="form4.oddometer_akhir"
                      :placeholder="__('Oddometer Akhir')"
                      type="number"
                      class="w-full text-xs"
                    />
                        
                    <InputError :error="form4.errors.oddometer_akhir" />
                  </div>
                  
                  <div class="flex flex-col items-start space-y-1">
                    <label for="hsd_akhir_kerja" class="font-bold text-xs">
                      {{ __('HSD Akhir Kerja') }}
                    </label>
                    
                    <Input
                      v-model="form4.hsd_akhir_kerja"
                      :placeholder="__('HSD Akhir Kerja')"
                      type="number"
                      class="w-full text-xs"
                    />
                        
                    <InputError :error="form4.errors.hsd_akhir_kerja" />
                  </div>
                  
                  <div class="flex flex-col items-start space-y-1">
                    <label for="konsumsi_hsd" class="font-bold text-xs">
                      {{ __('Konsumsi HSD') }}
                    </label>
                    
                    <Input
                      v-model="form4.konsumsi_hsd"
                      :placeholder="__('Konsumsi HSD')"
                      type="text"
                      class="w-full text-xs"
                    />
                        
                    <InputError :error="form4.errors.konsumsi_hsd" />
                  </div>
                <!-- </div> -->
                <div></div>
                
                
                <div class="col-span-full flex justify-between mt-4">
                  <Button class="bg-gray-600 text-white px-4 py-1 rounded disabled:opacity-50 text-xs" @click="currentStep1 = 1"> ← Kembali </Button>
                  <Button v-if="report.workresult?.id" class="bg-blue-700 hover:bg-blue-900 text-white px-4 py-1 rounded disabled:opacity-50 text-xs" @click="currentStep1 = 3" > Lanjut → </Button>
                  <Button v-if="!report.workresult?.id" class="bg-green-700 hover:bg-green-900 px-4 py-1 rounded float-right mr-2 text-xs" @click.prevent="submitworkresult()">Simpan</Button>
                  <!-- <Button v-if="report.workresult?.id" class="bg-blue-700 hover:bg-blue-900 px-4 py-1 rounded float-right mr-2 text-xs" @click.prevent="updateworkresult()">Edit</Button> -->
                </div>

              </div>

              <div v-if="currentStep1 === 3" class="tab-pane fade show active p-1">
                <div class="space-y-4 mb-6 text-sm">
                  <div class="space-y-2">
                      <p class="font-bold text-xs">1. Data Opname Resor Jalan Rel (Akhir):</p>
                      
                        <div class="p-2 border border-gray-200 rounded-lg shadow-sm hover:border-sky-500 transition duration-150">
                          <div class="flex flex-row items-start justify-between text-sm"> 
                              
                              <label for="mg1_awal" class="flex-1 text-xs text-black font-semi-bold pr-2">
                                  a. MG 1 (Lurusan)
                              </label>

                              <div class="flex space-x-4 flex-shrink-0 text-xs">
                                  
                                  <label 
                                      class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                      :class="{'text-sky-600 font-semibold': form13.ada === '1', 'text-gray-500': form13.ada !== '1'}"
                                  >
                                      <input 
                                          type="checkbox" 
                                          v-model="form13.ada" 
                                          true-value="1" 
                                          false-value="0"
                                          class="rounded text-sky-600 focus:ring-sky-500 w-3 h-3"
                                      >
                                      <span>Ada</span>
                                  </label>
                                  
                                  <label 
                                      class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                      :class="{'text-red-600 font-semibold': form13.tidak === '1', 'text-gray-500': form13.tidak !== '1'}"
                                  >
                                      <input 
                                          type="checkbox" 
                                          v-model="form13.tidak" 
                                          true-value="1" 
                                          false-value="0"
                                          class="rounded text-red-600 focus:ring-red-500 w-3 h-3"
                                      >
                                      <span>Tidak</span>
                                  </label>
                              </div>
                          </div>

                          <div class="mt-2 px-3">
                            <AttachmentInline 
                              :model="mglurusanakhir ?? {}" 
                              type="MgLurusanAkhir" 
                              :redaction="`Lampiran (MG 1 Lurusan Akhir)`"
                              :attachments="mglurusanakhir_attachments" 
                            />
                          </div>
                        </div>

                        <div class="p-2 border border-gray-200 rounded-lg shadow-sm hover:border-sky-500 transition duration-150">
                          <div class="flex flex-row items-start justify-between text-sm"> 
                              
                              <label for="mg1_awal" class="flex-1 text-xs text-black font-semi-bold pr-2">
                                  a. MG 2 (Lengkung)
                              </label>

                              <div class="flex space-x-4 flex-shrink-0 text-xs">
                                  
                                  <label 
                                      class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                      :class="{'text-sky-600 font-semibold': form14.ada === '1', 'text-gray-500': form14.ada !== '1'}"
                                  >
                                      <input 
                                          type="checkbox" 
                                          v-model="form14.ada" 
                                          true-value="1" 
                                          false-value="0"
                                          class="rounded text-sky-600 focus:ring-sky-500 w-3 h-3"
                                      >
                                      <span>Ada</span>
                                  </label>
                                  
                                  <label 
                                      class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                      :class="{'text-red-600 font-semibold': form14.tidak === '1', 'text-gray-500': form14.tidak !== '1'}"
                                  >
                                      <input 
                                          type="checkbox" 
                                          v-model="form14.tidak" 
                                          true-value="1" 
                                          false-value="0"
                                          class="rounded text-red-600 focus:ring-red-500 w-3 h-3"
                                      >
                                      <span>Tidak</span>
                                  </label>
                              </div>
                          </div>

                          <div class="mt-2 px-3">
                            <AttachmentInline 
                              :model="mglengkunganakhir ?? {}" 
                              type="MgLengkunganAkhir" 
                              :redaction="`Lampiran (MG 2 Lengkungan Akhir)`"
                              :attachments="mglengkunganakhir_attachments" 
                            />
                          </div>
                        </div>

                        <div class="p-2 border border-gray-200 rounded-lg shadow-sm hover:border-sky-500 transition duration-150">
                          <div class="flex flex-row items-start justify-between text-sm"> 
                              
                              <label for="mg1_awal" class="flex-1 text-xs text-black font-semi-bold pr-2">
                                  a. MG 3 (Wesel)
                              </label>

                              <div class="flex space-x-4 flex-shrink-0 text-xs">
                                  
                                  <label 
                                      class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                      :class="{'text-sky-600 font-semibold': form15.ada === '1', 'text-gray-500': form15.ada !== '1'}"
                                  >
                                      <input 
                                          type="checkbox" 
                                          v-model="form15.ada" 
                                          true-value="1" 
                                          false-value="0"
                                          class="rounded text-sky-600 focus:ring-sky-500 w-3 h-3"
                                      >
                                      <span>Ada</span>
                                  </label>
                                  
                                  <label 
                                      class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                      :class="{'text-red-600 font-semibold': form15.tidak === '1', 'text-gray-500': form15.tidak !== '1'}"
                                  >
                                      <input 
                                          type="checkbox" 
                                          v-model="form15.tidak" 
                                          true-value="1" 
                                          false-value="0"
                                          class="rounded text-red-600 focus:ring-red-500 w-3 h-3"
                                      >
                                      <span>Tidak</span>
                                  </label>
                              </div>
                          </div>

                          <div class="mt-2 px-3">
                            <AttachmentInline 
                              :model="mgweselakhir ?? {}" 
                              type="MgWeselAkhir" 
                              :redaction="`Lampiran (MG 3 Wesel Akhir)`"
                              :attachments="mgweselakhir_attachments" 
                            />
                          </div>
                        </div>
                  </div>

                  <div class="space-y-2">
                      <p class="font-bold text-xs">2. Data Perekaman (Akhir)</p>
                      <div class="p-2 border border-gray-200 rounded-lg shadow-sm hover:border-sky-500 transition duration-150">
                        <div class="flex flex-row items-start justify-between text-sm"> 
                              
                              <label for="mg1_awal" class="flex-1 text-xs text-black font-semi-bold pr-2">
                                  a. MG 1 (Lurusan)
                              </label>

                              <div class="flex space-x-4 flex-shrink-0 text-xs">
                                  
                                  <label 
                                      class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                      :class="{'text-sky-600 font-semibold': form16.ada === '1', 'text-gray-500': form16.ada !== '1'}"
                                  >
                                      <input 
                                          type="checkbox" 
                                          v-model="form16.ada" 
                                          true-value="1" 
                                          false-value="0"
                                          class="rounded text-sky-600 focus:ring-sky-500 w-3 h-3"
                                      >
                                      <span>Ada</span>
                                  </label>
                                  
                                  <label 
                                      class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                      :class="{'text-red-600 font-semibold': form16.tidak === '1', 'text-gray-500': form16.tidak !== '1'}"
                                  >
                                      <input 
                                          type="checkbox" 
                                          v-model="form16.tidak" 
                                          true-value="1" 
                                          false-value="0"
                                          class="rounded text-red-600 focus:ring-red-500 w-3 h-3"
                                      >
                                      <span>Tidak</span>
                                  </label>
                              </div>
                          </div>

                          <div class="mt-2 px-3">
                            <AttachmentInline 
                              :model="perekamanakhir ?? {}" 
                              type="PerekamanAkhir" 
                              :redaction="`Lampiran (Perekaman Akhir)`"
                              :attachments="perekamanakhir_attachments" 
                            />
                          </div>
                        </div>
                  </div>
                </div>

                <div class="flex justify-between mt-4 w-full"> 
                  <Button class="bg-gray-600 text-white px-4 py-1 rounded disabled:opacity-50 text-xs" @click="currentStep1 = 2"> ← Kembali </Button>
                  <Button class="bg-green-600 text-white px-4 py-1 rounded disabled:opacity-50 text-xs" type="button" @click="submitForm"> Simpan </Button>
                  <Button v-if="canApproveWorkResult" class="bg-blue-700 hover:bg-blue-900 px-4 py-1 rounded float-right mr-2 text-xs" @click.prevent="approveworkresult()">Approve</Button>
                </div>
              </div>              
            </div>
            <!-- section workresult -->

            <!-- section workresultok -->
            <div v-if="currentSection === 'workresultok' && report?.checksheetday?.checksheetworkresult?.mode === 'working'" class="tab-pane fade" id="list-workresultok" role="tabpanel" aria-labelledby="list-warmingup-list">
              <div class="grid grid-cols-1 gap-2 mb-4 md:grid-cols-2">
                <!-- <div class="grid grid-cols-1 gap-4 mb-4 md:grid-cols-2"> -->

                  <!-- <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Nama Mesin</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class=""> {{ report?.machine.name }} - {{ report?.machine.type }}- {{ report?.machine.nomor }} - {{ report?.machine.no_sarana }} ({{ report?.machine?.region.name }})</div>
                  </div> -->
                  
                  <!-- <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Nama Wilayah</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class=""> {{ report?.machine?.region.name || report?.region.name || '-' }}</div>
                  </div> -->
                  
                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Hari / Tanggal</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class="">{{ formatDateDay(report?.date || '-' ) }}</div>
                  </div>
                  
                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                      <div class="w-48 font-semibold text-xs">Nomor Mesin</div>
                      <div class="pr-2 text-xs">:</div>
                      <div class="lowercase first-letter:capitalize"> {{ report?.nomor_mesin || '-' }}</div>
                  </div>
                  
                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Cuaca</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class="lowercase first-letter:capitalize"> {{ report?.cuaca || '-' }}</div>
                  </div>
                  
                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Nomor Sarana</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class=""> {{ report?.nomor_sarana || '-' }}</div>
                  </div>
                  
                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Jenis / Tipe KPJR</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class=""> {{ report?.jenis_kpjr || '-' }}</div>
                  </div>
                  
                  <!-- <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Status</div>
                      <div class="pr-2 text-xs">:</div>
                      <div :class="[
                          'px-1 py-1 rounded text-xs font-semibold',
                          {
                              'bg-gray-100 text-gray-800': report.status === 'draft',
                              'bg-yellow-100 text-yellow-800': report.status === 'checksheet_done',
                              'bg-blue-100 text-blue-800': report.status === 'photo_uploaded',
                              'bg-green-500 text-white': report.status === 'finished',
                              'bg-blue-100 text-blue-800': report.status === 'work_done',
                              'bg-green-100 text-green-800': report.status === 'selesai',
                          },
                          ]"
                      >
                          {{
                          report.status === 'draft'
                              ? 'Process Working Order'
                              : report.status === 'checksheet_done'
                              ? 'Process Checksheet'
                              : report.status === 'photo_uploaded'
                              ? 'Process Upload'
                              : report.status === 'work_done'
                              ? 'Process Work Result'
                              : report.status === 'finished'
                              ? 'Approve KUPT'
                              : report.status === 'selesai'
                              ? 'Selesai'
                              : report.status
                          }}</div>
                  </div> -->
                </div>

                <div class="font-bold rounded-md text-xs py-1">A. DATA PEKERJAAN</div>
                <div  class="grid grid-cols-1 gap-2 mb-4 md:grid-cols-2">
                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Wilayah Resor</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class="lowercase first-letter:capitalize"> {{ report?.workresult?.wilayah || '-' }}</div>
                  </div>

                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Lokasi Stabling Awal</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class="lowercase first-letter:capitalize"> {{ report?.workresult?.lokasi_stabling_awal || '-' }}</div>
                  </div>

                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Petak Jalan</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class="lowercase first-letter:capitalize"> {{ report?.workresult?.petak_jalan || '-' }}</div>
                  </div>

                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Lokasi Stabling Akhir</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class="lowercase first-letter:capitalize"> {{ report?.workresult?.lokasi_stabling_akhir || '-' }}</div>
                  </div>

                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Kelas Jalan</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class="lowercase first-letter:capitalize"> {{ report?.workresult?.kelas_jalan || '-' }}</div>
                  </div>
                </div>
                
                <div class="space-y-1">
                  <!-- Baris 1 -->
                  <div class="flex flex-col items-start space-y-1">
                    <label class="md:col-span-2 text-xs font-semibold">Lokasi (Km/Hm)</label>
                    <div class="md:col-span-6 flex flex-wrap items-center gap-1 w-full"> 
                      <div class="flex items-center gap-1 flex-wrap flex-grow">
                        <div class="dot-input flex-1 min-w-[120px] text-xs text-center border-b border-gray-200 py-1 lowercase first-letter:capitalize"> {{ report?.workresult?.lokasi_awal1 || '-' }}</div> 
                        <span class="text-center text-xs font-semibold">s/d</span>
                        <div class="dot-input flex-1 min-w-[120px] text-xs text-center border-b border-gray-200 py-1 lowercase first-letter:capitalize"> {{ report?.workresult?.lokasi_akhir1 || '-' }}</div> 
                        <span class=" text-xs">[{{report?.workresult?.hu_hi1}}]</span>
                      </div>
                      
                      <div class="flex items-center gap-1 ml-auto flex-shrink-0">
                        <span class="whitespace-nowrap text-xs text-center font-semibold">Jumlah (Km/Hm)</span>
                        <span>:</span>
                        <div class="dot-input flex-1 min-w-[120px] text-xs text-center border-b border-gray-200 py-1 lowercase first-letter:capitalize"> {{ report?.workresult?.jumlah1 || '-' }}</div> 
                        <span class="text-xs">M'sp</span>
                      </div>
                    </div>
                  </div>

                  <!-- Baris 2 -->
                  <div class="flex flex-col items-start space-y-1">
                    <!-- <label class="md:col-span-2 text-xs invisible">Lokasi (Km/Hm)</label> -->
                    <div class="md:col-span-6 flex flex-wrap items-center gap-1 w-full"> 
                      <div class="flex items-center gap-1 flex-wrap flex-grow">
                        <div class="dot-input flex-1 min-w-[120px] text-xs text-center border-b border-gray-200 py-1 lowercase first-letter:capitalize"> {{ report?.workresult?.lokasi_awal2 || '-' }}</div> 
                        <span class="text-center text-xs font-semibold">s/d</span>
                        <div class="dot-input flex-1 min-w-[120px] text-xs text-center border-b border-gray-200 py-1 lowercase first-letter:capitalize"> {{ report?.workresult?.lokasi_akhir2 || '-' }}</div> 
                        <span class=" text-xs">[{{report?.workresult?.hu_hi2}}]</span>
                      </div>
                      
                      <div class="flex items-center gap-1 ml-auto flex-shrink-0">
                        <span class="whitespace-nowrap text-xs text-center font-semibold">Jumlah (Km/Hm)</span>
                        <span>:</span>
                        <div class="dot-input flex-1 min-w-[120px] text-xs text-center border-b border-gray-200 py-1 lowercase first-letter:capitalize"> {{ report?.workresult?.jumlah2 || '-' }}</div> 
                        <span class="text-xs">M'sp</span>
                      </div>
                    </div>
                  </div>

                  <!-- Baris 3 -->
                  <div class="flex flex-col items-start space-y-1">
                    <!-- <label class="md:col-span-2 text-xs invisible">Lokasi (Km/Hm)</label> -->
                    <div class="md:col-span-6 flex flex-wrap items-center gap-1 w-full"> 
                      <div class="flex items-center gap-1 flex-wrap flex-grow">
                        <div class="dot-input flex-1 min-w-[120px] text-xs text-center border-b border-gray-200 py-1 lowercase first-letter:capitalize"> {{ report?.workresult?.lokasi_awal3 || '-' }}</div> 
                        <span class="text-center text-xs font-semibold">s/d</span>
                        <div class="dot-input flex-1 min-w-[120px] text-xs text-center border-b border-gray-200 py-1 lowercase first-letter:capitalize"> {{ report?.workresult?.lokasi_akhir3 || '-' }}</div> 
                        <span class=" text-xs">[{{report?.workresult?.hu_hi3}}]</span>
                      </div>
                      
                      <div class="flex items-center gap-1 ml-auto flex-shrink-0">
                        <span class="whitespace-nowrap text-xs text-center font-semibold">Jumlah (Km/Hm)</span>
                        <span>:</span>
                        <div class="dot-input flex-1 min-w-[120px] text-xs text-center border-b border-gray-200 py-1 lowercase first-letter:capitalize"> {{ report?.workresult?.jumlah3 || '-' }}</div> 
                        <span class="text-xs">M'sp</span>
                      </div>
                    </div>
                  </div>

                  <!-- Baris Total -->
                  <div class="flex flex-col items-start space-y-1">
                    <!-- <label class="md:col-span-2 text-xs invisible">Lokasi (Km/Hm)</label> -->
                    <div class="md:col-span-6 flex flex-wrap items-center gap-1 w-full"> 
                      <div class="flex items-center gap-1 flex-wrap flex-grow">
                      </div>
                      
                      <div class="flex items-center gap-1 ml-auto flex-shrink-0">
                        <span class="whitespace-nowrap text-xs text-center font-semibold">Total (Km/Hm)</span>
                        <span>:</span>
                        <div class="dot-input flex-1 min-w-[120px] text-xs text-center border-b border-gray-200 py-1 lowercase first-letter:capitalize"> {{ report?.workresult?.total_distance || '-' }}</div> 
                        <span class="text-xs">M'sp</span>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="space-y-1">
                  <!-- Baris 1 -->
                  <div class="flex flex-col items-start space-y-1">
                    <label class="md:col-span-2 text-xs font-semibold">No Wesel</label>
                    <div class="md:col-span-6 flex flex-wrap items-center gap-1 w-full"> 
                      <div class="flex items-center gap-1 flex-wrap flex-grow">
                        <div class="dot-input flex-1 min-w-[120px] text-xs text-center border-b border-gray-200 py-1 lowercase first-letter:capitalize"> {{ report?.workresult?.no_wesel1 || '-' }}</div> 
                        <span class="text-center text-xs font-semibold">Km/Hm : </span>
                        <div class="dot-input flex-1 min-w-[120px] text-xs text-center border-b border-gray-200 py-1 lowercase first-letter:capitalize"> {{ report?.workresult?.km_hm1 || '-' }}</div> 
                        <span class=" text-xs">[{{report?.workresult?.hu_hi4}}]</span>
                      </div>
                      
                      <div class="flex items-center gap-1 ml-auto flex-shrink-0">
                        <span class="whitespace-nowrap text-xs text-center font-semibold">Jumlah Wesel</span>
                        <span>:</span>
                        <div class="dot-input flex-1 min-w-[120px] text-xs text-center border-b border-gray-200 py-1 lowercase first-letter:capitalize"> {{ report?.workresult?.jumlah_wesel1 || '-' }}</div> 
                        <span class="text-xs">Unit</span>
                      </div>
                    </div>
                  </div>

                  <!-- Baris 2 -->
                  <div class="flex flex-col items-start space-y-1">
                    <div class="md:col-span-6 flex flex-wrap items-center gap-1 w-full"> 
                      <div class="flex items-center gap-1 flex-wrap flex-grow">
                        <div class="dot-input flex-1 min-w-[120px] text-xs text-center border-b border-gray-200 py-1 lowercase first-letter:capitalize"> {{ report?.workresult?.no_wesel2 || '-' }}</div> 
                        <span class="text-center text-xs font-semibold">Km/Hm : </span>
                        <div class="dot-input flex-1 min-w-[120px] text-xs text-center border-b border-gray-200 py-1 lowercase first-letter:capitalize"> {{ report?.workresult?.km_hm2 || '-' }}</div> 
                        <span class=" text-xs">[{{report?.workresult?.hu_hi5}}]</span>
                      </div>
                      
                      <div class="flex items-center gap-1 ml-auto flex-shrink-0">
                        <span class="whitespace-nowrap text-xs text-center font-semibold">Jumlah Wesel</span>
                        <span>:</span>
                        <div class="dot-input flex-1 min-w-[120px] text-xs text-center border-b border-gray-200 py-1 lowercase first-letter:capitalize"> {{ report?.workresult?.jumlah_wesel2 || '-' }}</div> 
                        <span class="text-xs">Unit</span>
                      </div>
                    </div>
                  </div>

                  <!-- Baris 3 -->
                  <div class="flex flex-col items-start space-y-1">
                    <!-- <label class="md:col-span-2 text-xs invisible">Lokasi (Km/Hm)</label> -->
                    <div class="md:col-span-6 flex flex-wrap items-center gap-1 w-full"> 
                      <div class="flex items-center gap-1 flex-wrap flex-grow">
                        <div class="dot-input flex-1 min-w-[120px] text-xs text-center border-b border-gray-200 py-1 lowercase first-letter:capitalize"> {{ report?.workresult?.no_wesel3 || '-' }}</div> 
                        <span class="text-center text-xs font-semibold">Km/Hm : </span>
                        <div class="dot-input flex-1 min-w-[120px] text-xs text-center border-b border-gray-200 py-1 lowercase first-letter:capitalize"> {{ report?.workresult?.km_hm3 || '-' }}</div> 
                        <span class=" text-xs">[{{report?.workresult?.hu_hi6}}]</span>
                      </div>
                      
                      <div class="flex items-center gap-1 ml-auto flex-shrink-0">
                        <span class="whitespace-nowrap text-xs text-center font-semibold">Jumlah Wesel</span>
                        <span>:</span>
                        <div class="dot-input flex-1 min-w-[120px] text-xs text-center border-b border-gray-200 py-1 lowercase first-letter:capitalize"> {{ report?.workresult?.jumlah_wesel3 || '-' }}</div> 
                        <span class="text-xs">Unit</span>
                      </div>
                    </div>
                  </div>

                  <!-- Baris Total -->
                  <div class="flex flex-col items-start space-y-1">
                    <!-- <label class="md:col-span-2 text-xs invisible">Lokasi (Km/Hm)</label> -->
                    <div class="md:col-span-6 flex flex-wrap items-center gap-1 w-full"> 
                      <div class="flex items-center gap-1 flex-wrap flex-grow">
                      </div>
                      
                      <div class="flex items-center gap-1 ml-auto flex-shrink-0">
                        <span class="whitespace-nowrap text-xs text-center font-semibold">Total Wesel</span>
                        <span>:</span>
                        <div class="dot-input flex-1 min-w-[120px] text-xs text-center border-b border-gray-200 py-1 lowercase first-letter:capitalize"> {{ report?.workresult?.total_wesel || '-' }}</div> 
                        <span class="text-xs">Unit</span>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="p-1">                  
                  <!-- <div class="text-sm font-extrabold text-xs py-2">B. DATA UPLOAD</div>      -->
                  <p class="text-sm font-semibold border-b border-gray-200 text-xs py-2">
                      1. Data Opname Resor Jalan Rel (Awal) 
                  </p>

                  <div class="space-y-1">
                      
                      <div class="p-2 border-b border-gray-200 text-xs py-1">
                        <div class="flex flex-row items-start justify-between text-sm"> 
                            
                            <label for="mg1_awal" class="flex items-center gap-4 text-xs text-black font-semibold pr-2">
                              a. MG 1 (Lurusan) &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp

                              <AttachmentInline 
                                  :model="mglurusanawal ?? {}" 
                                  type="MgLurusanAwal" 
                                  :redaction="`Lampiran (MG 1 Lurusan)`"
                                  :attachments="mglurusanawal_attachments" 
                              />
                          </label>

                            <div class="flex space-x-4 flex-shrink-0 text-xs">
                                
                                <label 
                                    class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                    :class="{'text-sky-600 font-semibold': form7.ada === '1', 'text-black': form7.ada !== '1'}"
                                >
                                    <input 
                                        type="checkbox" 
                                        v-model="form7.ada" 
                                        true-value="1" 
                                        false-value="0"
                                        class="rounded text-sky-600 focus:ring-sky-500 w-3 h-3"
                                    >
                                    <span>Ada</span>
                                </label>
                                
                                <label 
                                    class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                    :class="{'text-red-600 font-semibold': form7.tidak === '1', 'text-black': form7.tidak !== '1'}"
                                >
                                    <input 
                                        type="checkbox" 
                                        v-model="form7.tidak" 
                                        true-value="1" 
                                        false-value="0"
                                        class="rounded text-red-600 focus:ring-red-500 w-3 h-3"
                                    >
                                    <span>Tidak</span>
                                </label>
                            </div>
                        </div>
                      </div>

                      <div class="p-2 border-b border-gray-200 text-xs py-1">
                        <div class="flex flex-row items-start justify-between text-sm"> 
                            
                            <label for="mg1_awal" class="flex items-center gap-4 text-xs text-black font-semibold pr-2">
                              b. MG 2 (Lengkung) &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp

                              <AttachmentInline 
                                  :model="mglengkunganawal ?? {}" 
                                  type="MgLengkunganAwal" 
                                  :redaction="`Lampiran (MG 2 Lengkung)`"
                                  :attachments="mglengkunganawal_attachments" 
                              />
                          </label>

                            <div class="flex space-x-4 flex-shrink-0 text-xs">
                                
                                <label 
                                    class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                    :class="{'text-sky-600 font-semibold': form8.ada === '1', 'text-black': form8.ada !== '1'}"
                                >
                                    <input 
                                        type="checkbox" 
                                        v-model="form8.ada" 
                                        true-value="1" 
                                        false-value="0"
                                        class="rounded text-sky-600 focus:ring-sky-500 w-3 h-3"
                                    >
                                    <span>Ada</span>
                                </label>
                                
                                <label 
                                    class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                    :class="{'text-red-600 font-semibold': form8.tidak === '1', 'text-black': form8.tidak !== '1'}"
                                >
                                    <input 
                                        type="checkbox" 
                                        v-model="form8.tidak" 
                                        true-value="1" 
                                        false-value="0"
                                        class="rounded text-red-600 focus:ring-red-500 w-3 h-3"
                                    >
                                    <span>Tidak</span>
                                </label>
                            </div>
                        </div>
                      </div>

                      <div class="p-2 border-b border-gray-200 text-xs py-1">
                        <div class="flex flex-row items-start justify-between text-sm"> 
                            
                            <label for="mg1_awal" class="flex items-center gap-4 text-xs text-black font-semibold pr-2">
                              c. MG 3 (Wesel) &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp

                              <AttachmentInline 
                                  :model="mgweselawal ?? {}" 
                                  type="MgWeselanAwal" 
                                  :redaction="`Lampiran (MG 3 Wesel)`"
                                  :attachments="mgweselawal_attachments" 
                              />
                          </label>

                            <div class="flex space-x-4 flex-shrink-0 text-xs">
                                
                                <label 
                                    class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                    :class="{'text-sky-600 font-semibold': form9.ada === '1', 'text-black': form9.ada !== '1'}"
                                >
                                    <input 
                                        type="checkbox" 
                                        v-model="form9.ada" 
                                        true-value="1" 
                                        false-value="0"
                                        class="rounded text-sky-600 focus:ring-sky-500 w-3 h-3"
                                    >
                                    <span>Ada</span>
                                </label>
                                
                                <label 
                                    class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                    :class="{'text-red-600 font-semibold': form9.tidak === '1', 'text-black': form9.tidak !== '1'}"
                                >
                                    <input 
                                        type="checkbox" 
                                        v-model="form9.tidak" 
                                        true-value="1" 
                                        false-value="0"
                                        class="rounded text-red-600 focus:ring-red-500 w-3 h-3"
                                    >
                                    <span>Tidak</span>
                                </label>
                            </div>
                        </div>
                      </div>

                  </div>

                  <p class="text-sm font-semibold border-b border-gray-200 text-xs py-2">
                      2. Data Pemeriksaan Silang
                  </p>

                  <div class="space-y-1">

                      <div class="p-2 border-b border-gray-200 text-xs py-1">
                        <div class="flex flex-row items-start justify-between text-sm"> 
                            
                            <label for="mg1_awal" class="flex items-center gap-4 text-xs text-black font-semibold pr-2">
                              a. KPJR &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp

                              <AttachmentInline 
                                  :model="pemeriksaansilangkpjr ?? {}" 
                                  type="PemeriksaanSilangKpjr" 
                                  :redaction="`Lampiran (Pemeriksaan Silang KPJR)`"
                                  :attachments="pemeriksaansilangkpjr_attachments" 
                              />
                          </label>

                            <div class="flex space-x-4 flex-shrink-0 text-xs">
                                
                                <label 
                                    class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                    :class="{'text-sky-600 font-semibold': form10.ada === '1', 'text-black': form10.ada !== '1'}"
                                >
                                    <input 
                                        type="checkbox" 
                                        v-model="form10.ada" 
                                        true-value="1" 
                                        false-value="0"
                                        class="rounded text-sky-600 focus:ring-sky-500 w-3 h-3"
                                    >
                                    <span>Ada</span>
                                </label>
                                
                                <label 
                                    class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                    :class="{'text-red-600 font-semibold': form10.tidak === '1', 'text-black': form10.tidak !== '1'}"
                                >
                                    <input 
                                        type="checkbox" 
                                        v-model="form10.tidak" 
                                        true-value="1" 
                                        false-value="0"
                                        class="rounded text-red-600 focus:ring-red-500 w-3 h-3"
                                    >
                                    <span>Tidak</span>
                                </label>
                            </div>
                        </div>
                      </div>

                      <div class="p-2 border-b border-gray-200 text-xs py-1">
                        <div class="flex flex-row items-start justify-between text-sm"> 
                            
                            <label for="mg1_awal" class="flex items-center gap-4 text-xs text-black font-semibold pr-2">
                              b. Lahan &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp

                              <AttachmentInline 
                                  :model="pemeriksaansilanglahan ?? {}" 
                                  type="PemeriksaanSilangLahan" 
                                  :redaction="`Lampiran (Pemeriksaan Silang Lahan)`"
                                  :attachments="pemeriksaansilanglahan_attachments" 
                              />
                          </label>

                            <div class="flex space-x-4 flex-shrink-0 text-xs">
                                
                                <label 
                                    class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                    :class="{'text-sky-600 font-semibold': form11.ada === '1', 'text-black': form11.ada !== '1'}"
                                >
                                    <input 
                                        type="checkbox" 
                                        v-model="form11.ada" 
                                        true-value="1" 
                                        false-value="0"
                                        class="rounded text-sky-600 focus:ring-sky-500 w-3 h-3"
                                    >
                                    <span>Ada</span>
                                </label>
                                
                                <label 
                                    class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                    :class="{'text-red-600 font-semibold': form11.tidak === '1', 'text-black': form11.tidak !== '1'}"
                                >
                                    <input 
                                        type="checkbox" 
                                        v-model="form11.tidak" 
                                        true-value="1" 
                                        false-value="0"
                                        class="rounded text-red-600 focus:ring-red-500 w-3 h-3"
                                    >
                                    <span>Tidak</span>
                                </label>
                            </div>
                        </div>
                      </div>

                  </div>

                  <p class="text-sm font-semibold border-b border-gray-200 text-xs py-2">
                      3. Data Perekaman (Awal)
                  </p>

                  <div class="space-y-1">
                      
                      <div class="p-2 border-b border-gray-200 text-xs py-1">
                        <div class="flex flex-row items-start justify-between text-sm"> 
                            
                            <label for="mg1_awal" class="flex items-center gap-4 text-xs text-black font-semibold pr-2">
                              a. Perekaman Awal &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp

                              <AttachmentInline 
                                  :model="perekamanawal ?? {}" 
                                  type="PerekamanaAwal" 
                                  :redaction="`Lampiran (Perekaman Awal)`"
                                  :attachments="perekamanawal_attachments" 
                              />
                          </label>

                            <div class="flex space-x-4 flex-shrink-0 text-xs">
                                
                                <label 
                                    class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                    :class="{'text-sky-600 font-semibold': form12.ada === '1', 'text-black': form12.ada !== '1'}"
                                >
                                    <input 
                                        type="checkbox" 
                                        v-model="form12.ada" 
                                        true-value="1" 
                                        false-value="0"
                                        class="rounded text-sky-600 focus:ring-sky-500 w-3 h-3"
                                    >
                                    <span>Ada</span>
                                </label>
                                
                                <label 
                                    class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                    :class="{'text-red-600 font-semibold': form12.tidak === '1', 'text-black': form12.tidak !== '1'}"
                                >
                                    <input 
                                        type="checkbox" 
                                        v-model="form12.tidak" 
                                        true-value="1" 
                                        false-value="0"
                                        class="rounded text-red-600 focus:ring-red-500 w-3 h-3"
                                    >
                                    <span>Tidak</span>
                                </label>
                            </div>
                        </div>
                      </div>
                  </div>

                  <p class="text-sm font-semibold border-b border-gray-200 text-xs py-2">
                      4. Data Opname Resor Jalan Rel (Akhir)
                  </p>

                  <div class="space-y-1">
                      
                      <div class="p-2 border-b border-gray-200 text-xs py-1">
                        <div class="flex flex-row items-start justify-between text-sm"> 
                            
                            <label for="mg1_awal" class="flex items-center gap-4 text-xs text-black font-semibold pr-2">
                              a. MG 1 (Lurusan) &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp

                              <AttachmentInline 
                                  :model="mglurusanakhir ?? {}" 
                                  type="MgLurusanAkhir" 
                                  :redaction="`Lampiran (MG 1 Lurusan Akhir)`"
                                  :attachments="mglurusanakhir_attachments" 
                              />
                          </label>

                            <div class="flex space-x-4 flex-shrink-0 text-xs">
                                
                                <label 
                                    class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                    :class="{'text-sky-600 font-semibold': form13.ada === '1', 'text-black': form13.ada !== '1'}"
                                >
                                    <input 
                                        type="checkbox" 
                                        v-model="form13.ada" 
                                        true-value="1" 
                                        false-value="0"
                                        class="rounded text-sky-600 focus:ring-sky-500 w-3 h-3"
                                    >
                                    <span>Ada</span>
                                </label>
                                
                                <label 
                                    class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                    :class="{'text-red-600 font-semibold': form13.tidak === '1', 'text-black': form13.tidak !== '1'}"
                                >
                                    <input 
                                        type="checkbox" 
                                        v-model="form13.tidak" 
                                        true-value="1" 
                                        false-value="0"
                                        class="rounded text-red-600 focus:ring-red-500 w-3 h-3"
                                    >
                                    <span>Tidak</span>
                                </label>
                            </div>
                        </div>
                      </div>

                      <div class="p-2 border-b border-gray-200 text-xs py-1">
                        <div class="flex flex-row items-start justify-between text-sm"> 
                            
                            <label for="mg1_awal" class="flex items-center gap-4 text-xs text-black font-semibold pr-2">
                              b. MG 2 (Lengkung) &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp

                              <AttachmentInline 
                                  :model="mglengkunganakhir ?? {}" 
                                  type="MgLengkunganAkhir" 
                                  :redaction="`Lampiran (MG 2 Lengkung Akhir)`"
                                  :attachments="mglengkunganakhir_attachments" 
                              />
                          </label>

                            <div class="flex space-x-4 flex-shrink-0 text-xs">
                                
                                <label 
                                    class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                    :class="{'text-sky-600 font-semibold': form14.ada === '1', 'text-black': form14.ada !== '1'}"
                                >
                                    <input 
                                        type="checkbox" 
                                        v-model="form14.ada" 
                                        true-value="1" 
                                        false-value="0"
                                        class="rounded text-sky-600 focus:ring-sky-500 w-3 h-3"
                                    >
                                    <span>Ada</span>
                                </label>
                                
                                <label 
                                    class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                    :class="{'text-red-600 font-semibold': form14.tidak === '1', 'text-black': form14.tidak !== '1'}"
                                >
                                    <input 
                                        type="checkbox" 
                                        v-model="form14.tidak" 
                                        true-value="1" 
                                        false-value="0"
                                        class="rounded text-red-600 focus:ring-red-500 w-3 h-3"
                                    >
                                    <span>Tidak</span>
                                </label>
                            </div>
                        </div>
                      </div>

                      <div class="p-2 border-b border-gray-200 text-xs py-1">
                        <div class="flex flex-row items-start justify-between text-sm"> 
                            
                            <label for="mg1_awal" class="flex items-center gap-4 text-xs text-black font-semibold pr-2">
                              c. MG 3 (Wesel) &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp

                              <AttachmentInline 
                                  :model="mgweselakhir ?? {}" 
                                  type="MgWeselanAkhir" 
                                  :redaction="`Lampiran (MG 3 Wesel Akhir)`"
                                  :attachments="mgweselakhir_attachments" 
                              />
                          </label>

                            <div class="flex space-x-4 flex-shrink-0 text-xs">
                                
                                <label 
                                    class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                    :class="{'text-sky-600 font-semibold': form15.ada === '1', 'text-black': form15.ada !== '1'}"
                                >
                                    <input 
                                        type="checkbox" 
                                        v-model="form15.ada" 
                                        true-value="1" 
                                        false-value="0"
                                        class="rounded text-sky-600 focus:ring-sky-500 w-3 h-3"
                                    >
                                    <span>Ada</span>
                                </label>
                                
                                <label 
                                    class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                    :class="{'text-red-600 font-semibold': form15.tidak === '1', 'text-black': form15.tidak !== '1'}"
                                >
                                    <input 
                                        type="checkbox" 
                                        v-model="form15.tidak" 
                                        true-value="1" 
                                        false-value="0"
                                        class="rounded text-red-600 focus:ring-red-500 w-3 h-3"
                                    >
                                    <span>Tidak</span>
                                </label>
                            </div>
                        </div>
                      </div>
                  </div>

                  <p class="text-sm font-semibold border-b border-gray-200 text-xs py-2">
                      5. Data Perekaman (Akhir)
                  </p>

                  <div class="space-y-1">
                      
                      <div class="p-2 border-b border-gray-200 text-xs py-1">
                        <div class="flex flex-row items-start justify-between text-sm"> 
                            
                            <label for="mg1_awal" class="flex items-center gap-4 text-xs text-black font-semibold pr-2">
                              a. Perekaman Awal &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp

                              <AttachmentInline 
                                  :model="perekamanakhir ?? {}" 
                                  type="PerekamanAkhir" 
                                  :redaction="`Lampiran (Perekaman Akhir)`"
                                  :attachments="perekamanakhir_attachments" 
                              />
                          </label>

                            <div class="flex space-x-4 flex-shrink-0 text-xs">
                                
                                <label 
                                    class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                    :class="{'text-sky-600 font-semibold': form16.ada === '1', 'text-black': form16.ada !== '1'}"
                                >
                                    <input 
                                        type="checkbox" 
                                        v-model="form16.ada" 
                                        true-value="1" 
                                        false-value="0"
                                        class="rounded text-sky-600 focus:ring-sky-500 w-3 h-3"
                                    >
                                    <span>Ada</span>
                                </label>
                                
                                <label 
                                    class="flex items-center space-x-1 cursor-pointer transition duration-100"
                                    :class="{'text-red-600 font-semibold': form16.tidak === '1', 'text-black': form16.tidak !== '1'}"
                                >
                                    <input 
                                        type="checkbox" 
                                        v-model="form16.tidak" 
                                        true-value="1" 
                                        false-value="0"
                                        class="rounded text-red-600 focus:ring-red-500 w-3 h-3"
                                    >
                                    <span>Tidak</span>
                                </label>
                            </div>
                        </div>
                      </div>
                  </div>

                </div><br>

                <div class="font-bold rounded-md text-xs py-1">B. DATA OPERASI MESIN</div>

                <div  class="grid grid-cols-1 gap-2 mb-4 md:grid-cols-2">
                  
                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Waktu Start Engine</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class="lowercase first-letter:capitalize"> {{ report?.waktu_start_engine?.slice(0, 5) || '-' }} Wib</div>
                  </div>
                  
                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Waktu Stop Engine</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class="lowercase first-letter:capitalize"> {{ report?.workresult?.waktu_stop_engine.slice(0, 5) || '-' }} Wib</div>
                  </div>
                  
                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Jam Traveling Awal</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class="lowercase first-letter:capitalize"> {{ report?.jam_traveling_awal || '-' }}</div>
                  </div>
                  
                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Jam Traveling Akhir</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class="lowercase first-letter:capitalize"> {{ report?.workresult?.jam_traveling_akhir || '-' }}</div>
                  </div>
                  
                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Jam Kerja Awal</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class="lowercase first-letter:capitalize"> {{ report?.jam_kerja_awal?.slice(0, 5) || '-' }}</div>
                  </div>
                  
                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Jam Kerja Akhir</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class="lowercase first-letter:capitalize"> {{ report?.workresult?.jam_kerja_akhir?.slice(0, 5) || '-' }}</div>
                  </div>
                  
                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Jam Mesin Awal</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class="lowercase first-letter:capitalize"> {{ report?.jam_mesin_awal || '-' }}</div>
                  </div>
                  
                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Jam Mesin Akhir</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class="lowercase first-letter:capitalize"> {{ report?.workresult?.jam_mesin_akhir || '-' }}</div>
                  </div>
                  
                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Jam Generator Awal</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class="lowercase first-letter:capitalize"> {{ report?.jam_generator_awal || '-' }}</div>
                  </div>
                  
                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Jam Generator Akhir</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class="lowercase first-letter:capitalize"> {{ report?.workresult?.jam_generator_akhir || '-' }}</div>
                  </div>
                  
                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Counter Tamping Awal</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class="lowercase first-letter:capitalize"> {{ report?.counter_tamping_awal || '-' }}</div>
                  </div>
                  
                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Counter Tamping Akhir</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class="lowercase first-letter:capitalize"> {{ report?.workresult?.counter_tamping_akhir || '-' }}</div>
                  </div>
                  
                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Oddometer Awal</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class="lowercase first-letter:capitalize"> {{ report?.oddometer_awal || '-' }}</div>
                  </div>
                  
                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Oddometer Akhir</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class="lowercase first-letter:capitalize"> {{ report?.workresult?.oddometer_akhir || '-' }}</div>
                  </div>
                  
                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">HSD Awal Kerja</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class="lowercase first-letter:capitalize"> {{ report?.hsd_awal_kerja || '-' }} %</div>
                  </div>
                  
                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">HSD Akhir Kerja</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class="lowercase first-letter:capitalize"> {{ report?.workresult?.hsd_akhir_kerja || '-' }} %</div>
                  </div>
                  
                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Konsumsi HSD</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class="lowercase first-letter:capitalize"> {{ report?.workresult?.konsumsi_hsd || '-' }}</div>
                  </div>
                </div>

                <div class="font-bold rounded-md text-xs py-1">C. DATA PERSONEL</div>
                  <div  class="grid grid-cols-1 gap-2 mb-4 md:grid-cols-2">
                  
                    <div class="flex items-center text-xs border-b border-gray-200 py-1">
                      <div class="w-48 font-semibold text-xs">Operator 1</div>
                      <div class="pr-2 text-xs">:</div>
                      <div class="uppercase">[{{ report?.operator1?.username }}] {{ report?.operator1?.name || '-' }} </div>
                    </div>

                    <div class="flex items-center text-xs border-b border-gray-200 py-1">
                      <div class="w-48 font-semibold text-xs">Pengawal 1</div>
                      <div class="pr-2 text-xs">:</div>
                      <div class="uppercase">[{{ report?.pengawal?.username }}] {{ report?.pengawal?.name || '-' }}  </div>
                    </div>
                              
                    <div class="flex items-center text-xs border-b border-gray-200 py-1">
                      <div class="w-48 font-semibold text-xs">Operator 2</div>
                      <div class="pr-2 text-xs">:</div>
                      <div class="uppercase">[{{ report?.operator2?.username }}] {{ report?.operator2?.name || '-' }} </div>
                    </div>

                    <div class="flex items-center text-xs border-b border-gray-200 py-1">
                      <div class="w-48 font-semibold text-xs">Pengawal 2</div>
                      <div class="pr-2 text-xs">:</div>
                      <div class="uppercase">[{{ report?.pengawal1?.username }}] {{ report?.pengawal1?.name || '-' }}  </div>
                    </div>
                              
                    <div class="flex items-center text-xs border-b border-gray-200 py-1">
                      <div class="w-48 font-semibold text-xs">Operator 3</div>
                      <div class="pr-2 text-xs">:</div>
                      <div class="uppercase">[{{ report?.operator3?.username }}] {{ report?.operator3?.name || '-' }} </div>
                    </div>
                </div>
                
                <p v-if="report?.operator_at1" class="text-xs font-bold text-blue-600">Operator 1 telah menyetujui pada tanggal : {{ formatDate(report?.operator_at1)}}</p>
                <p v-if="report?.operator_at2" class="text-xs font-bold text-blue-600">Operator 2 telah menyetujui pada tanggal : {{ formatDate(report?.operator_at2)}}</p>
                <p v-if="report?.operator_at3" class="text-xs font-bold text-blue-600">Operator 3 telah menyetujui pada tanggal : {{ formatDate(report?.operator_at3)}}</p>
                <p v-if="report?.approved_at" class="text-xs font-bold text-blue-600">Pengawal 1 telah menyetujui pada tanggal : {{ formatDate(report?.approved_at)}}</p>
                <p v-if="report?.approved_at1" class="text-xs font-bold text-blue-600">Pengawal 2 telah menyetujui pada tanggal : {{ formatDate(report?.approved_at1)}}</p>
                <p v-if="report?.kupt_at1" class="text-xs font-bold text-green-600">KUPT telah menyetujui pada tanggal : {{ formatDate(report?.kupt_at1)}}</p>
                <div></div>

                <div v-if="report.created_by_id == user.id && report.workresult?.id && !report.operator_at1" class="d-flex justify-content-end mt-3">
                    <Button 
                        class="bg-blue-700 hover:bg-blue-900 float-right mr-2 text-xs"
                        @click.prevent="approve(1)"
                    >
                        Approve Operator 1
                    </Button>
                </div>

                <div v-if="report.created_by_id == user.id && report.workresult?.id && report.operator_at1 && !report.operator_at2" class="d-flex justify-content-end mt-2">
                    <Button 
                        class="bg-blue-700 hover:bg-blue-900 float-right mr-2 text-xs"
                        @click.prevent="approve(2)"
                    >
                        Approve Operator 2
                    </Button>
                </div>

                <div v-if="report.created_by_id == user.id && report.workresult?.id && report.operator_at2 && !report.operator_at3" class="d-flex justify-content-end mt-2">
                    <Button 
                        class="bg-blue-700 hover:bg-blue-900 float-right mr-2 text-xs"
                        @click.prevent="approve(3)"
                    >
                        Approve Operator 3
                    </Button>
                </div>      
                
                <div v-if="report.approved_by === user.id && report.operator_at3 && !report.approved_at" class="d-flex justify-content-end mt-3">
                    <Button 
                        class="bg-blue-700 hover:bg-blue-900 float-right mr-2 text-xs"
                        @click.prevent="approvePengawal(1)"
                    >
                        Approve Pengawal 1
                    </Button>
                </div>    
                
                <div v-if="report.approved_by === user.id && report.approved_at && !report.approved_at1" class="d-flex justify-content-end mt-3">
                    <Button 
                        class="bg-blue-700 hover:bg-blue-900 float-right mr-2 text-xs"
                        @click.prevent="approvePengawal(2)"
                    >
                        Approve Pengawal 2
                    </Button>
                </div>

                <div class="flex justify-end mt-4 w-full"> 
                  <Button v-if="hasRole(['admin', 'Kepala UPT Mekanik']) && report.approved_at && !report.kupt_at1" class="bg-blue-700 hover:bg-blue-900 px-4 py-1 rounded float-right mr-2 text-xs" @click.prevent="approveKUPT()">Approve KUPT</Button>
                </div>
                
            </div>

            <div v-if="currentSection === 'workresultok' && report?.checksheetday?.checksheetworkresult?.mode === 'warmingup'" class="tab-pane fade" id="list-workresultok" role="tabpanel" aria-labelledby="list-warmingup-list">
              <div class="grid grid-cols-1 gap-2 mb-4 md:grid-cols-2">

                  <!-- <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Nama Mesin</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class=""> {{ report?.machine.name }} - {{ report?.machine.type }}- {{ report?.machine.nomor }} - {{ report?.machine.no_sarana }} ({{ report?.machine?.region.name }})</div>
                  </div> -->
                  
                  <!-- <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Nama Wilayah</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class=""> {{ report?.machine?.region.name || report?.region.name || '-' }}</div>
                  </div> -->
                  
                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Hari / Tanggal</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class="">{{ formatDateDay(report?.date || '-' ) }}</div>
                  </div>
                  
                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                      <div class="w-48 font-semibold text-xs">Nomor Mesin</div>
                      <div class="pr-2 text-xs">:</div>
                      <div class="capitalize"> {{ report?.nomor_mesin || '-' }}</div>
                  </div>
                  
                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Cuaca</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class="capitalize"> {{ report?.cuaca || '-' }}</div>
                  </div>
                  
                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Nomor Sarana</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class=""> {{ report?.nomor_sarana || '-' }}</div>
                  </div>
                  
                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Jenis / Tipe KPJR</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class=""> {{ report?.jenis_kpjr || '-' }}</div>
                  </div>
                  
                  <!-- <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Status</div>
                      <div class="pr-2 text-xs">:</div>
                      <div :class="[
                          'px-1 py-1 rounded text-xs font-semibold',
                          {
                              'bg-gray-100 text-gray-800': report.status === 'draft',
                              'bg-yellow-100 text-yellow-800': report.status === 'checksheet_done',
                              'bg-blue-100 text-blue-800': report.status === 'photo_uploaded',
                              'bg-green-500 text-white': report.status === 'finished',
                              'bg-blue-100 text-blue-800': report.status === 'work_done',
                              'bg-green-100 text-green-800': report.status === 'selesai',
                          },
                          ]"
                      >
                          {{
                          report.status === 'draft'
                              ? 'Process Working Order'
                              : report.status === 'checksheet_done'
                              ? 'Process Checksheet'
                              : report.status === 'photo_uploaded'
                              ? 'Process Upload'
                              : report.status === 'work_done'
                              ? 'Process Work Result'
                              : report.status === 'finished'
                              ? 'Approve KUPT'
                              : report.status === 'selesai'
                              ? 'Selesai'
                              : report.status
                          }}</div>
                  </div> -->
                </div>

                <div class="font-bold rounded-md text-xs py-1">A. DATA OPERASI MESIN</div>

                <div  class="grid grid-cols-1 gap-2 mb-4 md:grid-cols-2">
                  
                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Waktu Start Engine</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class="capitalize"> {{ report?.waktu_start_engine?.slice(0, 5) || '-' }} Wib</div>
                  </div>
                  
                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Waktu Stop Engine</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class="capitalize"> {{ report?.warmingup?.waktu_stop_engine?.slice(0, 5) || '-' }} Wib</div>
                  </div>
                  
                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Jam Traveling Awal</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class="capitalize"> {{ report?.jam_traveling_awal || '-' }}</div>
                  </div>
                  
                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Jam Traveling Akhir</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class="capitalize"> {{ report?.warmingup?.jam_traveling_akhir || '-' }}</div>
                  </div>
                  
                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Jam Kerja Awal</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class="capitalize"> {{ report?.jam_kerja_awal?.slice(0, 5) || '-' }}</div>
                  </div>
                  
                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Jam Kerja Akhir</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class="capitalize"> {{ report?.warmingup?.jam_kerja_akhir?.slice(0, 5) || '-' }}</div>
                  </div>
                  
                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Jam Mesin Awal</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class="capitalize"> {{ report?.jam_mesin_awal || '-' }}</div>
                  </div>
                  
                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Jam Mesin Akhir</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class="capitalize"> {{ report?.warmingup?.jam_mesin_akhir || '-' }}</div>
                  </div>
                  
                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Jam Generator Awal</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class="capitalize"> {{ report?.jam_generator_awal || '-' }}</div>
                  </div>
                  
                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Jam Generator Akhir</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class="capitalize"> {{ report?.warmingup?.jam_generator_akhir || '-' }}</div>
                  </div>
                  
                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Counter Tamping Awal</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class="capitalize"> {{ report?.counter_tamping_awal || '-' }}</div>
                  </div>
                  
                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Counter Tamping Akhir</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class="capitalize"> {{ report?.warmingup?.counter_tamping_akhir || '-' }}</div>
                  </div>
                  
                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Oddometer Awal</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class="capitalize"> {{ report?.oddometer_awal || '-' }}</div>
                  </div>
                  
                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Oddometer Akhir</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class="capitalize"> {{ report?.warmingup?.oddometer_akhir || '-' }}</div>
                  </div>
                  
                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">HSD Awal Kerja</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class="capitalize"> {{ report?.hsd_awal_kerja || '-' }} %</div>
                  </div>
                  
                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">HSD Akhir Kerja</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class="capitalize"> {{ report?.warmingup?.hsd_akhir_kerja || '-' }} %</div>
                  </div>
                  
                  <div class="flex items-center text-xs border-b border-gray-200 py-1">
                    <div class="w-48 font-semibold text-xs">Konsumsi HSD</div>
                    <div class="pr-2 text-xs">:</div>
                    <div class="capitalize"> {{ report?.warmingup?.konsumsi_hsd || '-' }}</div>
                  </div>
                  
                </div>
                
                <div class="font-bold rounded-md text-xs py-1">B. DATA PERSONEL</div>
                  <div  class="grid grid-cols-1 gap-2 mb-4 md:grid-cols-2">
                  
                    <div class="flex items-center text-xs border-b border-gray-200 py-1">
                      <div class="w-48 font-semibold text-xs">Operator 1</div>
                      <div class="pr-2 text-xs">:</div>
                      <div class="uppercase">[{{ report?.operator1?.username }}] {{ report?.operator1?.name || '-' }} </div>
                    </div>

                    <div class="flex items-center text-xs border-b border-gray-200 py-1">
                      <div class="w-48 font-semibold text-xs">Pengawal 1</div>
                      <div class="pr-2 text-xs">:</div>
                      <div class="uppercase">[{{ report?.pengawal?.username }}] {{ report?.pengawal?.name || '-' }}  </div>
                    </div>
                              
                    <div class="flex items-center text-xs border-b border-gray-200 py-1">
                      <div class="w-48 font-semibold text-xs">Operator 2</div>
                      <div class="pr-2 text-xs">:</div>
                      <div class="uppercase">[{{ report?.operator2?.username }}] {{ report?.operator2?.name || '-' }} </div>
                    </div>

                    <div class="flex items-center text-xs border-b border-gray-200 py-1">
                      <div class="w-48 font-semibold text-xs">Pengawal 2</div>
                      <div class="pr-2 text-xs">:</div>
                      <div class="uppercase">[{{ report?.pengawal1?.username }}] {{ report?.pengawal1?.name || '-' }}  </div>
                    </div>
                              
                    <div class="flex items-center text-xs border-b border-gray-200 py-1">
                      <div class="w-48 font-semibold text-xs">Operator 3</div>
                      <div class="pr-2 text-xs">:</div>
                      <div class="uppercase">[{{ report?.operator3?.username }}] {{ report?.operator3?.name || '-' }} </div>
                    </div>
                </div>
                
                <p v-if="report?.operator_at1" class="text-xs font-bold text-blue-600">Operator 1 telah menyetujui pada tanggal : {{ formatDate(report?.operator_at1)}}</p>
                <p v-if="report?.operator_at2" class="text-xs font-bold text-blue-600">Operator 2 telah menyetujui pada tanggal : {{ formatDate(report?.operator_at2)}}</p>
                <p v-if="report?.operator_at3" class="text-xs font-bold text-blue-600">Operator 3 telah menyetujui pada tanggal : {{ formatDate(report?.operator_at3)}}</p>
                <p v-if="report?.approved_at" class="text-xs font-bold text-blue-600">Pengawal 1 telah menyetujui pada tanggal : {{ formatDate(report?.approved_at)}}</p>
                <p v-if="report?.approved_at1" class="text-xs font-bold text-blue-600">Pengawal 2 telah menyetujui pada tanggal : {{ formatDate(report?.approved_at1)}}</p>
                <p v-if="report?.kupt_at1" class="text-xs font-bold text-green-600">KUPT telah menyetujui pada tanggal : {{ formatDate(report?.kupt_at1)}}</p>
                <div></div>

                <div v-if="report.created_by_id == user.id && report.warmingup?.id && !report.operator_at1" class="d-flex justify-content-end mt-3">
                    <Button 
                        class="bg-blue-700 hover:bg-blue-900 float-right mr-2 text-xs"
                        @click.prevent="approve(1)"
                    >
                        Approve Operator 1
                    </Button>
                </div>

                <div v-if="report.created_by_id == user.id && report.warmingup?.id && report.operator_at1 && !report.operator_at2" class="d-flex justify-content-end mt-2">
                    <Button 
                        class="bg-blue-700 hover:bg-blue-900 float-right mr-2 text-xs"
                        @click.prevent="approve(2)"
                    >
                        Approve Operator 2
                    </Button>
                </div>

                <div v-if="report.created_by_id == user.id && report.warmingup?.id && report.operator_at2 && !report.operator_at3" class="d-flex justify-content-end mt-2">
                    <Button 
                        class="bg-blue-700 hover:bg-blue-900 float-right mr-2 text-xs"
                        @click.prevent="approve(3)"
                    >
                        Approve Operator 3
                    </Button>
                </div>      
                
                <div v-if="report.approved_by === user.id && report.operator_at3 && !report.approved_at" class="d-flex justify-content-end mt-3">
                    <Button 
                        class="bg-blue-700 hover:bg-blue-900 float-right mr-2 text-xs"
                        @click.prevent="approvePengawal(1)"
                    >
                        Approve Pengawal 1
                    </Button>
                </div>    
                
                <div v-if="report.approved_by === user.id && report.approved_at && !report.approved_at1" class="d-flex justify-content-end mt-3">
                    <Button 
                        class="bg-blue-700 hover:bg-blue-900 float-right mr-2 text-xs"
                        @click.prevent="approvePengawal(2)"
                    >
                        Approve Pengawal 2
                    </Button>
                </div>

                <div class="flex justify-end mt-4 w-full"> 
                  <Button v-if="hasRole(['admin', 'Kepala UPT Mekanik']) && report.approved_at && !report.kupt_at1" class="bg-blue-700 hover:bg-blue-900 px-4 py-1 rounded float-right mr-2 text-xs" @click.prevent="approveKUPT()">Approve KUPT</Button>
                </div>

            </div>
            <!-- section workresultok -->    
          </div>
          </div>
        </div>
        </div>
      </template>
    </Card>

  </DashboardLayout>
</template>