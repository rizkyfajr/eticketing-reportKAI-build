<script setup>
import { onMounted, onUnmounted, nextTick, ref, computed, toRef, watch } from 'vue'
import { useForm, Link, usePage } from '@inertiajs/inertia-vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Card from '@/Components/Card.vue'
import Select from '@vueform/multiselect'
import Button from '@/Components/Button.vue'
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
    klasifikasi: props.report?.klasifikasi || '',
    type: props.report?.type || '',
    lokasi_stabling_awal: props.report?.lokasi_stabling_awal || '',
    jenis_kpjr: props.report?.jenis_kpjr || '',
    nomor_mesin: props.report?.nomor_mesin || '',
    nomor_sarana: props.report?.nomor_sarana || '',
    waktu_start_engine: props.report?.waktu_start_engine?.slice(0, 5) || '',
    jam_traveling_awal: props.report?.jam_traveling_awal || '',
    jam_kerja_awal: props.report?.jam_kerja_awal?.slice(0, 5)  || '',
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
    nipp: props.report?.nipp || '',
    nama_pengawal: props.report?.nama_pengawal || '',
    nipp1: props.report?.nipp1 || '',
    nama_pengawal1: props.report?.nama_pengawal1 || '',
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
    satuan: props.warmingup?.satuan || '',
    konsumsi_hsd: props.warmingup?.konsumsi_hsd || '',
    operator_by1: props.warmingup?.operator_by1 || '',
    operator_by2: props.warmingup?.operator_by2 || '',
    operator_by3: props.warmingup?.operator_by3 || '',
    approved_by: props.warmingup?.approved_by || '',
    approved_by1: props.warmingup?.approved_by1 || '',
    note: props.warmingup?.note || null,
    user_id: props.warmingup?.warmingup_user.map(warmingup_user => warmingup_user.user_id) || [],
    mode: '',
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
    no_lengkung1: props.workresult?.no_lengkung1 || null,
    radius1: props.workresult?.radius1 || null,
    jumlah_lengkung1: props.workresult?.jumlah_lengkung1 || null,
    no_lengkung2: props.workresult?.no_lengkung2 || null,
    radius2: props.workresult?.radius2 || null,
    jumlah_lengkung2: props.workresult?.jumlah_lengkung2 || null,
    no_lengkung3: props.workresult?.no_lengkung3 || null,
    radius3: props.workresult?.radius3 || null,
    jumlah_lengkung3: props.workresult?.jumlah_lengkung3 || null,
    total_lengkung: props.workresult?.total_lengkung || null,
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
    hu_hi7: props.workresult?.hu_hi7 || null,
    hu_hi8: props.workresult?.hu_hi8 || null,
    hu_hi9: props.workresult?.hu_hi9 || null,
    km_hm_lengkung1: props.workresult?.km_hm_lengkung1 || null,
    km_hm_lengkung2: props.workresult?.km_hm_lengkung2 || null,
    km_hm_lengkung3: props.workresult?.km_hm_lengkung3 || null,
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

const isRowValid = (item) => {
    return (
        item.cek == 1 ||
        item.tambahan == 1 ||
        item.service == 1 ||
        item.ganti == 1 ||
        (item.kiri_depan && item.kiri_depan.trim() !== "") ||
        (item.kanan_depan && item.kanan_depan.trim() !== "")
    );
};

const isCurrentGroupValid = computed(() => {
    return currentGroupResults.value.every(item => isRowValid(item));
});

const isAllGroupValid = computed(() => {
    return groups.value.every(groupName => {
        const rows = props.results.filter(r => r.group_name === groupName);
        return rows.every(item => isRowValid(item));
    });
});

const isAllGroupsFilled = computed(() => {
  if (!props.results || props.results.length === 0) return false;

  return props.results.every(item => {
    const hasCheckbox = item.cek == 1 || item.tambahan == 1 || item.service == 1 || item.ganti == 1;
    const hasValue = (item.kiri_depan && item.kiri_depan !== "") || 
                     (item.kanan_depan && item.kanan_depan !== "");

    return hasCheckbox || hasValue;
  });
});

const currentStep = ref(1)
const currentStep1 = ref(1)
const showNextButton = ref(false)
const currentGroupIndex = ref(0)

// const groups = computed(() => {
//   const uniqueGroups = [...new Set(props.results.map(r => r.group_name))]
//   return uniqueGroups
// })

const groups = computed(() => {
  const orderedGroups = [];
  props.results.forEach(item => {
    if (!orderedGroups.includes(item.group_name)) {
      orderedGroups.push(item.group_name);
    }
  });
  return orderedGroups;
});

const currentGroupResults = computed(() => {
    if (groups.value.length === 0) return [];
    return props.results.filter(r => r.group_name === groups.value[currentGroupIndex.value]);
});

const isFirstGroup = computed(() => currentGroupIndex.value === 0);

const isLastGroup = computed(() => currentGroupIndex.value === groups.value.length - 1);

const nextGroup = () => {
  if (!isCurrentGroupValid.value) {
      Swal.fire({
          icon: "warning",
          title: "Data belum lengkap!",
          text: "Setiap baris wajib mengisi minimal satu kolom (cek/tambahan/Service/Ganti).",
      });
      return;
  }

  if (!isLastGroup.value) {
      currentGroupIndex.value++;
  }
};

const prevGroup = () => {
    if (!isFirstGroup.value) {
        currentGroupIndex.value--;
    }
};

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

//disabled dailycheck
const isGroupCompleted = computed(() => {
    const results = currentGroupResults.value; 
    const isSignedByOperator = props.report.operator_at3 !== null && props.report.operator_at3 !== '';

    if (isSignedByOperator) {
        return true;
    }

    if (!results || results.length === 0) {
        return false;
    }

    const allItemsCompleted = results.every(item => {
        const isCheckboxChecked = item.cek === 1 || item.tambahan === 1 || item.service === 1 || item.ganti === 1;
        const isTextFieldFilled = item.kiri_depan?.trim() !== '' || item.kanan_depan?.trim() !== '' || item.keterangan?.trim() !== '';

        return isCheckboxChecked || isTextFieldFilled;
    });

    return allItemsCompleted;
});

const isSignedByOperator = computed(() => {
    return props.report.operator_at3 !== null && props.report.operator_at3 !== '';
});

const isCheckboxDisabled = computed(() => {
    // return isGroupCompleted.value || isSignedByOperator.value;
    return !!props.report.warmingup?.id
});

const isTextFieldDisabled = computed(() => {
    return !!props.report.warmingup?.id
});

const toggleResult = async (item, field) => {
  const previousValue = item[field];
  item[field] = item[field] == 1 ? 0 : 1;

  try {
    const response = await axios.post(route("checksheetday-results.autosave"), {
      working_report_id: props.report.id,
      check_sheet_master_day_id: item.check_sheet_master_day_id,
      cek: item.cek ?? 0,
      tambahan: item.tambahan ?? 0,
      service: item.service ?? 0,
      ganti: item.ganti ?? 0,
      kiri_depan: item.kiri_depan ?? '',
      kanan_depan: item.kanan_depan ?? '',
      keterangan: item.keterangan ?? '',
    });
    
      Swal.fire({
        toast: true,
        position: "top-end",
        icon: "success",
        title: previousValue == null ? "Berhasil Disimpan" : "Berhasil Tersimpan",
        timer: 600,  
        timerProgressBar: true,
        showConfirmButton: false,
        showCloseButton: false,
        backdrop: false,
        didOpen: (toast) => {
          toast.style.animation = "none"; 
        }
      });
  } catch (error) {
    console.error("Autosave failed:", error);
    Swal.fire("Error", "Gagal menyimpan data!", "error");
  }
};

const saveTextField = async (item) => {
  try {
    await axios.post(route("checksheetday-results.autosave"), {
      working_report_id: props.report.id,
      check_sheet_master_day_id: item.check_sheet_master_day_id,
      cek: item.cek ?? 0,
      tambahan: item.tambahan ?? 0,
      service: item.service ?? 0,
      ganti: item.ganti ?? 0,
      kiri_depan: item.kiri_depan ?? '',
      kanan_depan: item.kanan_depan ?? '',
      keterangan: item.keterangan ?? '',
    });

      Swal.fire({
        toast: true,
        position: "top-end",
        icon: "success",
        title: "Berhasil Disimpan",
        timer: 600,  
        timerProgressBar: true,
        showConfirmButton: false,
        showCloseButton: false,
        backdrop: false,
        didOpen: (toast) => {
          toast.style.animation = "none"; 
        }
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
  const report = props.report

  if (!report || !user?.id) return false

  if (user.id !== report.created_by_id) return false

  if (!isAllGroupsFilled.value) return false

  return true
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
      title: "Apakah Anda yakin??",
      text: `Data tidak dapat diubah ketika klik tombol "Iya"`,
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Iya",
      cancelButtonText: "Batal",
    });

    if (!confirm.isConfirmed) return;

    Swal.fire({
      title: "Menyimpan...",
      didOpen: () => Swal.showLoading(),
      allowOutsideClick: false,
    });

    await axios.post(route("working-reports.setMode"), {
      working_report_id: props.report.id,
      mode: mode,
    });

    props.report.mode = mode;

    await Swal.fire({
      icon: "success",
      title: "Berhasil!",
      text: mode === 'warmingup' ? "Selesai Warming Up!" : "Mode berhasil diperbarui!",
      timer: 1500,
      showConfirmButton: false,
    });

    if (mode === 'warmingup') {
      window.location.reload();
    }

    // setTimeout(() => window.location.reload(), 1000);

  } catch (error) {
    console.error(error);
    Swal.fire({
      icon: "error",
      title: "Gagal update mode!",
    });
  }
};

const submitwarmingup = (modeValue = null) => {
  Swal.fire({
    title: 'Menyimpan data...',
    didOpen: () => Swal.showLoading(),
    allowOutsideClick: false,
  })

  form3.mode = modeValue;

  form3.post(route('warming-up.store', props.report.id), {
    onSuccess: (page) => {
      const updatedWarmingup = page.props.report?.warmingup;

      if (updatedWarmingup) {
        props.report.warmingup = updatedWarmingup;
      }

      if (!modeValue) { 
        showForm1.value = false; 
      }

      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: `Data disimpan. Mode diatur ke: ${modeValue}`,
        timer: 1500,
        showConfirmButton: false,
      })
      // setTimeout(() => window.location.reload(), 1000);
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

// WORKING //
const handleInput = (field, event) => {
    let val = event.target.value;
    
    if (val.length > 6) {
        form4[field] = val.slice(0, 6);
        
        Swal.fire({
            icon: 'warning',
            title: 'Maksimal 6 digit',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
        });
    }
}

watch(
    [() => form4.lokasi_awal1, () => form4.lokasi_akhir1, 
     () => form4.lokasi_awal2, () => form4.lokasi_akhir2,
     () => form4.lokasi_awal3, () => form4.lokasi_akhir3],
    () => {
        for (let i = 1; i <= 3; i++) {
            const awal = parseInt(form4['lokasi_awal' + i]) || 0;
            const akhir = parseInt(form4['lokasi_akhir' + i]) || 0;
            const selisih = akhir - awal;
            form4['jumlah' + i] = selisih > 0 ? selisih : 0;
        }
    }
)

// Fungsi untuk membersihkan karakter 
const parseKmHm = (val) => {
    if (!val) return 0;
    return parseInt(val.toString().replace(/[^0-9]/g, '')) || 0;
}

watch(
    [() => form4.lokasi_awal1, () => form4.lokasi_akhir1],
    ([awal, akhir]) => {
        const hasil = parseKmHm(akhir) - parseKmHm(awal);
        form4.jumlah1 = hasil > 0 ? hasil : 0;
    }
)

watch(
    [() => form4.lokasi_awal2, () => form4.lokasi_akhir2],
    ([awal, akhir]) => {
        const hasil = parseKmHm(akhir) - parseKmHm(awal);
        form4.jumlah2 = hasil > 0 ? hasil : 0;
    }
)

watch(
    [() => form4.lokasi_awal3, () => form4.lokasi_akhir3],
    ([awal, akhir]) => {
        const hasil = parseKmHm(akhir) - parseKmHm(awal);
        form4.jumlah3 = hasil > 0 ? hasil : 0;
    }
)

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

const totalLengkung = computed(() => {
    return (
        (parseInt(form4.jumlah_lengkung1) || 0) +
        (parseInt(form4.jumlah_lengkung2) || 0) +
        (parseInt(form4.jumlah_lengkung3) || 0)
    )
})

watch(totalDistance, (val) => form4.total_distance = val)
watch(totalWesel, (val) => form4.total_wesel = val)
watch(totalLengkung, (val) => form4.total_lengkung = val)

// Otomatis jumlah_wesel menjadi 1 jika no_wesel diisi
watch(() => form4.no_wesel1, (newVal) => {
    form4.jumlah_wesel1 = newVal ? 1 : 0
})
watch(() => form4.no_wesel2, (newVal) => {
    form4.jumlah_wesel2 = newVal ? 1 : 0
})
watch(() => form4.no_wesel3, (newVal) => {
    form4.jumlah_wesel3 = newVal ? 1 : 0
})
// WORKING //

const submitworkresult = () => {
  form4.working_report_id = props.report.id;

  Swal.fire({
    title: 'Menyimpan data...',
    didOpen: () => Swal.showLoading(),
    allowOutsideClick: false,
  })

  form4.post(route('work-results.store', props.report.id), {
    preserveScroll: true,
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

    form3.working_report_id = reportId;

    switch (section) {
      case 'report':
        form.first_section = data.first_section;
        break;
      case 'checksheetday':
        form1.checksheetday = data.checksheetday;
        break;
      case 'warmingup':
        if (data.warmingup) {
          form3.waktu_stop_engine = data.warmingup.waktu_stop_engine;
          form3.jam_kerja_akhir = data.warmingup.jam_kerja_akhir;
          form3.jam_mesin_akhir = data.warmingup.jam_mesin_akhir;
        }
        showForm1.value = true;
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

const isDailyCheckCompleted = computed(() => {
  return isAllGroupValid.value;
  // return isEverythingCompleted.value;
});

const isWarmingUpMode = computed(() => props.report?.mode === 'warmingup');
const isWorkingOrderMode = computed(() => props.report?.mode === 'working');

const canAccessWarmingUp = computed(() => {
  // return isWarmingUpMode.value && isAllGroupValid.value;
  // return isAllGroupValid.value;
  return isDailyCheckCompleted.value || !!props.report.warmingup?.id;
});

const canAccessWorking = computed(() => {
  return isWorkingOrderMode.value && isAllGroupValid.value;
});

// const isWorkResult = computed(() => {
//   const result = props.report
//   return ['working', 'warmingup'].includes(result?.mode)
// })

const isWorkResult = computed(() => {
    const report = props.report;

    if (report.mode === 'warmingup') {
        return true;
    }
    
    if (report.mode === 'working' && report.workresult?.id) {
        return true;
    }

    return false;

});

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

// pemilihan mesin otomatis
// watch(
//   () => form.machine_id,
//   (newVal) => {
//     if (!newVal) {
//       form.jenis_kpjr = ''
//       form.nomor_mesin = ''
//       form.nomor_sarana = ''
//       form.region_id = ''
//       form.klasifikasi = ''
//       form.type = ''
//       return
//     }

//     const selected = props.machines.find(m => m.id === newVal)

//     if (selected) {
//       // form.jenis_kpjr = `${selected.name} ${selected.type}`
//       form.jenis_kpjr = selected.type || ''
//       form.nomor_mesin = selected.nomor || ''
//       form.nomor_sarana = selected.no_sarana || ''
//       form.region_id = selected.region_id || ''
//       form.klasifikasi = selected.classification?.name || ''
//       form.type = selected.name || ''
//     }
//   }
// )

// Handler untuk QR Scanner
const handleQrScanned = (data) => {
  try {
    form.machine_id = data.machine_id

    form.jenis_kpjr = `${data.name} ${data.type}`
    form.nomor_mesin = data.nomor || ''
    form.nomor_sarana = data.no_sarana || ''
    form.region_id = data.region_id || ''

    Swal.fire({
      icon: 'success',
      title: 'Berhasil!',
      text: 'Data mesin berhasil di-scan dari QR Code',
      timer: 1500,
      showConfirmButton: false,
    })
  } catch (err) {
    console.error('Error processing QR data:', err)
  }
}

const handleQrError = (error) => {
  Swal.fire({
    icon: 'error',
    title: 'Error!',
    text: error,
  })
}

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

// watch(() => props.perekamanawal_attachments, (val) => {
//     if (val?.length > 0) {
//         form6.ada = "1";
//         form6.tidak = "0";
//     }
// }, { immediate: true });

function oppositeWatcher(form) {
    watch(() => form.ada, (v) => { if (v === '1') form.tidak = '0' })
    watch(() => form.tidak, (v) => { if (v === '1') form.ada = '0' })
}

oppositeWatcher(form7)
oppositeWatcher(form8)
oppositeWatcher(form9)
oppositeWatcher(form10)
oppositeWatcher(form11)
oppositeWatcher(form12)

const showForm1 = ref(false)

onMounted(() => {
  const report = props.report

  if (
    report?.mglurusanawal ||
    report?.mglengkunganawal ||
    report?.mgweselawal ||
    report?.pemeriksaansilangkpjr ||
    report?.pemeriksaansilanglahan ||
    report?.perekamanawal
  ) {
    showForm1.value = true
  }
});

const validateForms = () => {
    const failedValidations = [];
    
    const isOneOfMgUploaded = 
        (props.mglurusanawal_attachments?.length > 0) || 
        (props.mglengkunganawal_attachments?.length > 0) || 
        (props.mgweselawal_attachments?.length > 0);

    if (!isOneOfMgUploaded) {
        failedValidations.push("Data Opname Rel Jalan Awal (IP 2, IG 2, atau IG 3) (Wajib pilih salah satu)");
    }
    
    if (!(props.pemeriksaansilangkpjr_attachments?.length > 0)) {
        failedValidations.push("Data Pemeriksaan Silang KPJR");
    }

    if (!(props.pemeriksaansilanglahan_attachments?.length > 0)) {
        failedValidations.push("Data Pemeriksaan Silang Lahan");
    }

    // if (!(props.perekamanawal_attachments?.length > 0)) {
    //     failedValidations.push("Data Perekaman (Awal)");
    // }

    return failedValidations;
};

const submit = () => {
  Swal.fire({
    title: 'Konfirmasi Simpan',
    text: "Apakah Anda yakin data yang dimasukkan sudah benar dan lengkap?",
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#16a34a',
    cancelButtonColor: '#4b5563', 
    confirmButtonText: 'Ya, Simpan!',
    cancelButtonText: 'Batal'
  }).then((result) => {
    if (result.isConfirmed) {
      Swal.fire({
        title: 'Menyimpan data...',
        didOpen: () => Swal.showLoading(),
        allowOutsideClick: false,
      })

      form.post(route('working-reports.store'), {
        onSuccess: () => {
          Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Working Report berhasil disimpan.',
            timer: 1200,
            showConfirmButton: false,
          });
          // setTimeout(() => window.location.reload(), 1000);
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
  })
}

const submitForms = () => {
  const failedValidations = validateForms();

  if (failedValidations.length > 0) {
      let errorMessage = 'Wajib upload pada tahap berikut: <br><br>';
      errorMessage += '<ul>';
      failedValidations.forEach(name => {
          errorMessage += `<li class="text-red-500 text-sm">- ${name}</li>`;
      });
      errorMessage += '</ul>';

      Swal.fire({
          icon: 'warning',
          title: 'Mohon Lengkapi Data!',
          html: errorMessage,
          confirmButtonText: 'OK',
      });
      return;
  }
  Swal.fire({
      title: 'Menyimpan data...',
      didOpen: () => Swal.showLoading(),
      allowOutsideClick: false,
  });

  const payload = {
    working_report_id: props.report.id,

    mg1: form7.data(),
    mg2: form8.data(),
    mg3: form9.data(),
    silang_kpjr: form10.data(),
    silang_lahan: form11.data(),
    perekaman: form12.data(),
  };

  axios.post(route('working-reports.submit-form'), payload)
  .then((res) => {
      Swal.fire({
          icon: 'success',
          title: 'Berhasil!',
          text: 'Data berhasil disimpan.',
          timer: 1000,
          showConfirmButton: false,
      });

      if (props.pemeriksaansilanglahan) {
          props.pemeriksaansilanglahan.ada = '1';
      }

      // setTimeout(() => {
      //     window.location.href = res.data.redirect;
      // }, 1000);
  });

};

const formatDateTime = (date) => {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    
    return `${year}-${month}-${day}T${hours}:${minutes}`;
};

onMounted(() => {
    const now = new Date();
    form.date = formatDateTime(now);
});

const classificationOptions = computed(() => {
  const seen = new Set();
  return props.machines
    .map(m => m.classification)
    .filter(c => {
      if (!c || seen.has(c.id)) return false;
      seen.add(c.id);
      return true;
    })
    .map(c => ({ label: c.name, value: c.name }));
});

const typeOptions = computed(() => {
  const types = props.machines.map(m => m.name);
  return [...new Set(types)]
    .filter(t => t)
    .map(t => ({ label: t, value: t }));
});

const seriOptions = computed(() => {
  const jenis_kpjr = props.machines.map(m => m.type);
  return [...new Set(jenis_kpjr)]
    .filter(s => s)
    .map(s => ({ label: s, value: s }));
});

const nomorMesinOptions = computed(() => {
  const nomorList = props.machines.map(m => m.nomor);
  return [...new Set(nomorList)]
    .filter(n => n)
    .map(n => ({ label: n.toString(), value: n }));
});

watch(() => form.nomor_mesin, (newVal) => {
  const selectedMachine = props.machines.find(m => m.nomor === newVal);
  
  if (selectedMachine) {
    form.machine_id = selectedMachine.id;
    form.nomor_sarana = selectedMachine.no_sarana; 
    form.region_id = selectedMachine.region_id;
    
  } else {
    form.nomor_sarana = '';
    form.machine_id = '';
    form.region_id = '';
  }
});

const esc = e => e.key === 'Escape' && close()
onMounted(() => window.addEventListener('keydown', esc))
onUnmounted(() => window.removeEventListener('keydown', esc))
</script>

<style src="@vueform/multiselect/themes/default.css"></style>
<style src="@/multiselect.css"></style>
<style>
  .step-wrapper {
    @apply flex flex-col items-center text-center px-2;
  }
  .step-circle {
    @apply w-8 h-8 flex items-center justify-center rounded-full text-xs font-bold;
  }
  .step-line {
    @apply flex-1 h-[2px] bg-gray-300 mx-1;
  }
</style>

<style scoped>
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

.modern-select :deep(.vs__dropdown-toggle) {
  padding: 6px 0;
  border-radius: 0.75rem;
  border-color: #e2e8f0;
}
</style>

<style>
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>

<style scoped>
.modern-input {
  @apply w-full border-gray-200 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 rounded-lg text-xs transition-all placeholder:text-gray-300 shadow-sm py-2;
}
.modern-input-sub {
  @apply w-full border-gray-200 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 rounded-lg text-xs transition-all bg-white shadow-sm py-2;
}
.animate-fade-in {
  animation: fadeIn 0.4s ease-out;
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

.modern-input {
  @apply border-gray-200 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 rounded-lg text-xs transition-all placeholder:text-gray-300 shadow-sm;
}
.modern-input-sub {
  @apply border-gray-100 focus:ring-2 focus:ring-blue-500/10 focus:border-blue-400 rounded-md text-[11px] h-9 transition-all bg-gray-50/30;
}
.modern-select-sub {
  @apply w-full border-gray-100 rounded-md px-2 h-9 text-[11px] bg-gray-50/30 focus:ring-2 focus:ring-blue-500/10 focus:border-blue-400 outline-none transition-all;
}

.animate-fade-in {
  animation: fadeIn 0.4s ease-out;
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

input[type="checkbox"] {
  cursor: pointer;
}
</style>

<template>

  <DashboardLayout :title="__('Working Order')">
    <div class="w-full px-2 py-2 overflow-x-auto no-scrollbar">
      <div class="flex items-start justify-between min-w-[600px] md:min-w-full px-0">
        
        <div class="flex flex-col items-center group relative flex-1" 
            @click.prevent="fetch('report', report)" 
            :class="{ 'cursor-pointer': report.id }">
          <div class="w-8 h-8 rounded-full flex items-center justify-center transition-all duration-300 shadow-md z-10 border-4 border-white"
              :class="{
                'bg-blue-600 ring-4 ring-blue-100 scale-110': currentSection === 'report' || !report.id,
                'bg-emerald-500': currentSection !== 'report' && report.id,
                // 'bg-rose-400': !report.id
              }">
            <span class="text-white font-bold text-sm">1</span>
          </div>
          <p class="text-[10px] md:text-xs mt-2 font-bold uppercase tracking-wider transition-colors"
            :class="currentSection === 'report' ? 'text-gray-700' : 'text-gray-700'">Preparation</p>
        </div>

        <div class="flex-grow h-[2px] self-center mb-4 bg-gray-400 transition-colors mx-[-15px]" 
            :class="{'bg-emerald-400': report.id}"></div>

        <div class="flex flex-col items-center group relative flex-1"
            @click.prevent="hasWorkingOrder && fetch('checksheetday', report)"
            :class="{ 'cursor-pointer': hasWorkingOrder }">
          <div class="w-8 h-8 rounded-full flex items-center justify-center transition-all duration-300 shadow-md z-10 border-4 border-white"
              :class="{
                'bg-blue-600 ring-4 ring-blue-100 scale-110': currentSection === 'checksheetday', 
                'bg-emerald-500': currentSection !== 'checksheetday' && isDailyCheckCompleted, 
                'bg-rose-500 opacity-50': !hasWorkingOrder || !report.id,
                'bg-gray-400': currentSection !== 'checksheetday' && !isDailyCheckCompleted && hasWorkingOrder,
              }">
            <span class="text-white font-bold text-sm">2</span>
          </div>
          <p class="text-[10px] md:text-xs mt-2 font-bold uppercase tracking-wider text-gray-700">Daily Check</p>
        </div>

        <div class="flex-grow h-[2px] self-center mb-4 bg-gray-400 mx-[-15px]"
            :class="{'bg-emerald-400': isDailyCheckCompleted}"></div>

        <div class="flex flex-col items-center group relative flex-1"
            @click.prevent="canAccessWarmingUp && fetch('warmingup', report)"
            :class="{ 'cursor-pointer': canAccessWarmingUp }">
          <div class="w-8 h-8 rounded-full flex items-center justify-center transition-all duration-300 shadow-md z-10 border-4 border-white"
              :class="{
                'bg-blue-600 ring-4 ring-blue-100 scale-110': currentSection === 'warmingup', 
                'bg-emerald-500': currentSection !== 'warmingup' && (report.warmingup?.id || isDailyCheckCompleted),
                'bg-rose-500 opacity-50': !canAccessWarmingUp,
                'bg-gray-400': currentSection !== 'warmingup' && canAccessWarmingUp && !report.warmingup?.id
              }">
            <span class="text-white font-bold text-sm">3</span>
          </div>
          <p class="text-[10px] md:text-xs mt-2 font-bold uppercase tracking-wider text-gray-700">Warming Up</p>
        </div>

        <div class="flex-grow h-[2px] self-center mb-4 bg-gray-400 mx-[-15px]"
            :class="{'bg-emerald-400': report.warmingup?.id}"></div>

        <div class="flex flex-col items-center group relative flex-1"
            @click.prevent="canAccessWorking && fetch('workresult', report)"
            :class="{ 'cursor-pointer': canAccessWorking }">
          <div class="w-8 h-8 rounded-full flex items-center justify-center transition-all duration-300 shadow-md z-10 border-4 border-white"
              :class="{
                'bg-blue-600 ring-4 ring-blue-100 scale-110': currentSection === 'workresult',
                'bg-emerald-500': currentSection !== 'workresult' && report.workresult?.id,
                'bg-rose-500 opacity-50': !canAccessWorking,
                'bg-gray-400': currentSection !== 'workresult' && canAccessWorking && !report.workresult?.id,
              }">
            <span class="text-white font-bold text-sm">4</span>
          </div>
          <p class="text-[10px] md:text-xs mt-2 font-bold uppercase tracking-wider text-gray-700">Working</p>
        </div>

        <div v-if="isWorkResult" class="flex-grow h-[2px] self-center mb-4 bg-gray-400 mx-[-15px]"
            :class="{'bg-emerald-400': report.workresult?.id}"></div>

        <div v-if="isWorkResult" class="flex flex-col items-center group relative flex-1"
            @click.prevent="(report.workresult?.id || warmingup?.id) && fetch('workresultok', report)"
            :class="{ 'cursor-pointer': report.workresult?.id || warmingup?.id }">
          <div class="w-8 h-8 rounded-full flex items-center justify-center transition-all duration-300 shadow-md z-10 border-4 border-white"
              :class="{
                'bg-blue-600 ring-4 ring-blue-100 scale-110': currentSection === 'workresultok',
                'bg-emerald-500': currentSection !== 'workresultok' && (report.workresult?.id || warmingup?.id),
                'bg-rose-500': !report.workresult?.id && !warmingup?.id
              }">
            <span class="text-white font-bold text-sm">5</span>
          </div>
          <p class="text-[10px] md:text-xs mt-2 font-bold uppercase tracking-wider text-gray-700">Result</p>
        </div>

      </div>
    </div>

    <Card class="bg-white shadow-lg border border-solid" style="border-radius:.625rem; margin-bottom: 1rem;">
      <template #body>

      <div class="flex justify-between items-center border-b border-gray-300 bg-gray-300">
        <div class="flex items-center space-x-2">
          <div class="w-1 h-6 bg-red-600 rounded-lg"></div>
          <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wide">Working Order</h3>
        </div>
        <div class="flex items-center space-x-2">
          <span v-if="report.maintenance_orders && report.maintenance_orders.length > 0"
                class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-xs font-semibold">
            {{ report.maintenance_orders.length }} Maintenance Order
          </span>

          <Link :href="`/maintenance-orders/create-from-wr/${report.id}`"
                class="px-2 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-semibold transition flex items-center space-x-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.308 17c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <span class="text-xs font-semibold text-white uppercase tracking-wide">Report Maintenance</span>
          </Link>
        </div>
      </div>
        
      <div class="flex flex-col space-y-1 p-1">
        <div class="flex flex-col md:flex-row mt-2 md:space-y-0 md:space-x-0">
          <div class=" p-2 w-full" id="list-section1" role="tabpanel" aria-labelledby="list-section1-list">
          <div class="flex flex-col space-y-1">

            <!-- section working report -->

					  <div v-if="currentSection === 'report'" class="tab-pane fade show active" id="list-report" role="tabpanel" aria-labelledby="list-report-list">   
              
                <form @submit.prevent="submit" class="gap-4 p-2">   
                  <div class="max-w-6xl mx-auto p-2 space-y-8 bg-gray-50 antialiased rounded-lg">

                    <section class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                      <div class="flex items-center space-x-2 mb-6 border-b pb-4">
                        <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                          </svg>
                        </div>
                        <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Informasi Umum</h2>
                      </div>

                      <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="flex flex-col space-y-1.5">
                          <label class="text-[11px] font-bold text-gray-800 uppercase tracking-wider ml-1">Tanggal & Waktu</label>
                          <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </div>
                            <Input
                              v-model="form.date"
                              type="datetime-local"
                              class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-800 cursor-not-allowed transition-all"
                              disabled
                            />
                          </div>
                        </div>

                        <div class="flex flex-col space-y-1.5">
                          <label class="text-[11px] font-bold text-gray-800 uppercase tracking-wider ml-1">Kondisi Cuaca</label>
                          <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-transform group-hover:scale-110">
                              <span v-if="!form.cuaca">☁️</span>
                              <span v-else-if="form.cuaca === 'Cerah'">☀️</span>
                              <span v-else-if="form.cuaca === 'Hujan'">🌧️</span>
                              <span v-else-if="form.cuaca === 'Berawan'">🌥️</span>
                              <span v-else>✨</span>
                            </div>
                            <select 
                              v-model="form.cuaca"
                              class="w-full pl-10 pr-10 py-2.5 bg-white border border-gray-200 rounded-xl text-sm appearance-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all shadow-sm cursor-pointer"
                              required
                            >
                              <option value="" disabled>Pilih kondisi...</option>
                              <option value="Cerah">Cerah</option>
                              <option value="Berawan">Berawan</option>
                              <option value="Hujan">Hujan</option>
                              <option value="Panas">Panas</option>
                              <option value="Dingin">Dingin</option>
                              <option value="Berangin">Berangin</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </div>
                          </div>
                          <InputError :error="form.errors.cuaca" />
                        </div>

                        <div class="flex flex-col space-y-1.5">
                          <label class="text-[11px] font-bold text-gray-800 uppercase tracking-wider ml-1">Awal Jam Kerja</label>
                          <Input v-model="form.jam_kerja_awal" type="time" class="w-full px-4 py-2.5 bg-blue-50/50 border-blue-100 rounded-xl text-sm focus:ring-blue-500" />
                          <InputError :error="form.errors.jam_kerja_awal" />
                        </div>

                        <div v-show="false" class="flex flex-col space-y-1.5">
                          <label class="text-[11px] font-bold text-gray-800 uppercase tracking-wider ml-1">Wilayah</label>
                          <Select
                            v-model="form.region_id"
                            :options="regions.map(region => ({
                                label: `${region.name}`,
                                value: region.id,
                            }))"
                            disabled
                          />
                      </div>

                      </div>
                    </section>

                    <section class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                      <div class="flex items-center space-x-2 mb-6 border-b pb-4">
                        <div class="p-2 bg-emerald-50 rounded-lg text-emerald-600">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                          </svg>
                        </div>
                        <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Data Personil</h2>
                      </div>

                      <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                        
                        <div v-for="i in 3" :key="'op'+i" class="flex flex-col space-y-1.5">
                          <label class="text-[11px] font-bold text-gray-800 uppercase tracking-wider ml-1">Operator {{ i }}</label>
                          <Select
                            v-model="form['operator_by'+i]"
                            :options="users.filter(user => user.id !== 1 && user.id !== 3).map(user => ({
                              label: `[${user.username}] ${user.name.toUpperCase()}`,
                              value: user.id,
                            }))"
                            :searchable="true"
                            class="modern-select custom-tiny-text" 
                            style="font-size: 0.7rem !important;" 
                          >
                            <template #option="{ option }">
                              <span class="text-[11px] leading-tight antialiased">
                                {{ option.label }}
                              </span>
                            </template>

                            <template #singleLabel="{ option }">
                              <span class="text-[11px]">
                                {{ option.label }}
                              </span>
                            </template>
                          </Select>
                        </div>

                        <div class="flex flex-col space-y-1.5">
                          <label class="text-[11px] font-bold text-gray-800 uppercase tracking-wider ml-1">NIPP Pengawal 1</label>
                          <Input v-model="form.nipp" type="number" class="w-full px-4 py-2.5 border-gray-200 rounded-xl text-sm focus:ring-blue-500" />
                        </div>
                        <div class="flex flex-col space-y-1.5">
                          <label class="text-[11px] font-bold text-gray-800 uppercase tracking-wider ml-1">Nama Pengawal 1</label>
                          <Input v-model="form.nama_pengawal" class="w-full px-4 py-2.5 border-gray-200 rounded-xl text-sm focus:ring-blue-500" />
                        </div>

                        <div class="flex flex-col space-y-1.5">
                          <label class="text-[11px] font-bold text-gray-800 uppercase tracking-wider ml-1">NIPP Pengawal 2</label>
                          <Input v-model="form.nipp1" type="number" class="w-full px-4 py-2.5 border-gray-200 rounded-xl text-sm focus:ring-blue-500" />
                        </div>
                        <div class="flex flex-col space-y-1.5">
                          <label class="text-[11px] font-bold text-gray-800 uppercase tracking-wider ml-1">Nama Pengawal 2</label>
                          <Input v-model="form.nama_pengawal1" class="w-full px-4 py-2.5 border-gray-200 rounded-xl text-sm focus:ring-blue-500" />
                        </div>

                      </div>
                    </section>

                    <section class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                      <div class="flex items-center space-x-2 mb-6 border-b pb-4">
                        <div class="p-2 bg-purple-50 rounded-lg text-purple-600">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                          </svg>
                        </div>
                        <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Data Spesifikasi Mesin</h2>
                      </div>

                      <!-- <div class="grid grid-cols-1 gap-6">
                        <div class="flex flex-col space-y-1.5">
                            <label class="text-[11px] font-bold text-gray-800 uppercase tracking-wider ml-1">Pilih Unit Mesin</label>
                            <Select
                              v-model="form.machine_id"
                              :options="machines.map(m => ({ 
                                label: `[${m.nomor}] ${m.name} - ${m.type} - ${m.no_sarana} (${m.region?.name})`, 
                                value: m.id 
                              }))"
                              class="modern-select shadow-sm"
                              style="font-size: 11px !important;" 
                            >
                              <template #option="{ option }">
                                <span class="text-[11px] leading-tight">
                                  {{ option.label }}
                                </span>
                              </template>
                            </Select>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                            <div v-for="field in ['klasifikasi', 'type', 'jenis_kpjr', 'nomor_sarana', 'nomor_mesin']" :key="field" class="flex flex-col space-y-1.5">
                              <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider ml-1">
                                {{ field === 'jenis_kpjr' ? 'Seri' : field.replace('_', ' ') }}
                              </label>
                              <Input v-model="form[field]" class="w-full px-3 py-2 bg-gray-50 border-gray-200 rounded-lg text-xs" readonly />
                            </div>
                        </div>
                      </div> -->

                      <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
    
                        <div class="flex flex-col space-y-1.5">
                          <label class="text-[11px] font-bold text-gray-800 uppercase tracking-wider ml-1">Klasifikasi</label>
                          <Select
                            v-model="form.klasifikasi"
                            :options="classificationOptions"
                            :searchable="true"
                            class="modern-select shadow-sm"
                            style="font-size: 11px !important;"
                          />
                        </div>

                        <div class="flex flex-col space-y-1.5">
                          <label class="text-[11px] font-bold text-gray-800 uppercase tracking-wider ml-1">Type</label>
                          <Select
                            v-model="form.type"
                            :options="typeOptions"
                            :searchable="true"
                            class="modern-select shadow-sm"
                            style="font-size: 11px !important;"
                          />
                        </div>

                        <div class="flex flex-col space-y-1.5">
                          <label class="text-[11px] font-bold text-gray-800 uppercase tracking-wider ml-1">Seri</label>
                          <Select
                            v-model="form.jenis_kpjr"
                            :options="seriOptions"
                            :searchable="true"
                            class="modern-select shadow-sm"
                            style="font-size: 11px !important;"
                          />
                        </div>

                        <div class="flex flex-col space-y-1.5">
                          <label class="text-[11px] font-bold text-gray-800 uppercase tracking-wider ml-1">Nomor Mesin</label>
                          <Select
                            v-model="form.nomor_mesin"
                            :options="nomorMesinOptions"
                            :searchable="true"
                            class="modern-select shadow-sm"
                            style="font-size: 11px !important;"
                          />
                        </div>

                        <div class="flex flex-col space-y-1.5">
                          <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider ml-1">Nomor Sarana</label>
                          <Input 
                            v-model="form.nomor_sarana" 
                            class="w-full px-3 py-2 bg-gray-100 border-gray-200 rounded-lg text-xs font-semibold text-blue-600" 
                            readonly 
                            placeholder="Otomatis..."
                          />
                        </div>

                      </div>
                    </section>

                    <section class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                      <div class="flex items-center space-x-2 mb-6 border-b pb-4">
                        <div class="p-2 bg-orange-50 rounded-lg text-orange-600 font-bold">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                          </svg>
                        </div>
                        <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Data Operasi Mesin</h2>
                      </div>

                      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        <!-- <div class="flex flex-col space-y-1.5">
                          <label class="text-[11px] font-bold text-gray-800 uppercase tracking-wider ml-1">Wilayah</label>
                          <Select v-model="form.region_id" :options="regions.map(r => ({label: r.name, value: r.id}))" disabled />
                        </div> -->

                        <div class="flex flex-col space-y-1.5">
                          <label class="text-[11px] font-bold text-gray-800 uppercase tracking-wider ml-1">Start Engine</label>
                          <Input v-model="form.waktu_start_engine" type="time" required class="w-full px-4 py-2.5 border-gray-200 rounded-xl text-sm" />
                        </div>

                        <div class="flex flex-col space-y-1.5">
                          <label class="text-[11px] font-bold text-gray-800 uppercase tracking-wider ml-1">Jam Traveling</label>
                          <Input v-model="form.jam_traveling_awal" type="text" class="w-full px-4 py-2.5 border-gray-200 rounded-xl text-sm" />
                        </div>

                        <div class="flex flex-col space-y-1.5">
                          <label class="text-[11px] font-bold text-gray-800 uppercase tracking-wider ml-1">Jam Mesin</label>
                          <Input v-model="form.jam_mesin_awal" type="text" required class="w-full px-4 py-2.5 border-gray-200 rounded-xl text-sm" />
                        </div>

                        <div class="flex flex-col space-y-1.5">
                          <label class="text-[11px] font-bold text-gray-800 uppercase tracking-wider ml-1">Jam Generator</label>
                          <Input v-model="form.jam_generator_awal" type="text" required class="w-full px-4 py-2.5 border-gray-200 rounded-xl text-sm" />
                        </div>

                        <div class="flex flex-col space-y-1.5">
                          <label class="text-[11px] font-bold text-gray-800 uppercase tracking-wider ml-1">Counter Tamping</label>
                          <Input v-model="form.counter_tamping_awal" type="number" required class="w-full px-4 py-2.5 border-gray-200 rounded-xl text-sm" />
                        </div>

                        <div class="flex flex-col space-y-1.5">
                          <label class="text-[11px] font-bold text-gray-800 uppercase tracking-wider ml-1">Oddometer</label>
                          <Input v-model="form.oddometer_awal" type="number" required class="w-full px-4 py-2.5 border-gray-200 rounded-xl text-sm" />
                        </div>

                        <div class="flex flex-col space-y-1.5">
                          <label class="text-[11px] font-bold text-gray-800 uppercase tracking-wider ml-1">Pengisian HSD</label>
                          <Input v-model="form.hsd_awal_kerja" type="number" class="w-full px-4 py-2.5 border-gray-200 rounded-xl text-sm" />
                        </div>

                        <!-- <div v-for="label in ['Jam Mesin Awal', 'Jam Generator Awal', 'Counter Tamping Awal', 'Oddometer Awal', 'HSD Awal Kerja']" 
                            :key="label" class="flex flex-col space-y-1.5">
                          <label class="text-[11px] font-bold text-gray-800 uppercase tracking-wider ml-1">{{ label }}</label>
                          <Input type="number" class="w-full px-4 py-2.5 border-gray-200 rounded-xl text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-50" :placeholder="label" />
                        </div> -->
                      </div>
                    </section>

                  </div>
                  

                    <div v-if="!report.id" class="flex items-center justify-end space-x-3">
                      <Link
                        :href="route('working-reports.index')"
                        class="bg-gray-600 text-white text-xs px-3 py-1 rounded-md hover:bg-gray-700 mt-10 flex items-center space-x-1"
                      >
                        <p class="font-bold text-xs">Kembali</p>
                      </Link>

                      <Button
                        type="submit"
                        class="bg-green-600 text-white text-xs px-3 py-1 rounded-md hover:bg-green-700 mt-10"
                      >
                      <p class="font-bold text-xs">Simpan</p>
                      </Button>
                    </div>
                </form>

                <div v-if="report.maintenance_orders && report.maintenance_orders.length > 0" class="mt-6 border-t pt-4">
                  <h4 class="text-md font-bold mb-3 text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Maintenance Orders Terkait ({{ report.maintenance_orders.length }})
                  </h4>

                  <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200 rounded-lg text-xs">
                      <thead class="bg-gray-100">
                        <tr>
                          <th class="px-4 py-2 text-left font-semibold">ID</th>
                          <th class="px-4 py-2 text-left font-semibold">Kategori</th>
                          <th class="px-4 py-2 text-left font-semibold">Judul</th>
                          <th class="px-4 py-2 text-left font-semibold">Mesin</th>
                          <th class="px-4 py-2 text-left font-semibold">Status</th>
                          <th class="px-4 py-2 text-left font-semibold">Severity</th>
                          <th class="px-4 py-2 text-center font-semibold">Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="mo in report.maintenance_orders" :key="mo.id" class="border-t hover:bg-gray-50">
                          <td class="px-4 py-2">#{{ mo.id }}</td>
                          <td class="px-4 py-2">
                            <span :class="mo.category === 'planned' ? 'px-2 py-1 bg-blue-100 text-blue-700 rounded' : 'px-2 py-1 bg-red-100 text-red-700 rounded'">
                              {{ mo.category === 'planned' ? 'Planned' : 'Unplanned' }}
                            </span>
                          </td>
                          <td class="px-4 py-2 font-medium">{{ mo.title }}</td>
                          <td class="px-4 py-2">{{ mo.machine?.name }}</td>
                          <td class="px-4 py-2">
                            <span :class="{
                              'px-2 py-1 bg-yellow-100 text-yellow-700 rounded': mo.status === 'pending',
                              'px-2 py-1 bg-blue-100 text-blue-700 rounded': mo.status === 'in_progress',
                              'px-2 py-1 bg-green-100 text-green-700 rounded': mo.status === 'completed'
                            }">
                              {{ mo.status || 'pending' }}
                            </span>
                          </td>
                          <td class="px-4 py-2">
                            <span :class="{
                              'px-2 py-1 bg-gray-100 text-gray-700 rounded': mo.severity === 'low',
                              'px-2 py-1 bg-yellow-100 text-yellow-700 rounded': mo.severity === 'medium',
                              'px-2 py-1 bg-orange-100 text-orange-700 rounded': mo.severity === 'high',
                              'px-2 py-1 bg-red-100 text-red-700 rounded': mo.severity === 'critical'
                            }">
                              {{ mo.severity }}
                            </span>
                          </td>
                          <td class="px-4 py-2 text-center">
                            <Link :href="`/maintenance-orders/${mo.id}/edit`"
                                  class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs">
                              Detail
                            </Link>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>

            </div>
            <!-- section working report -->

            <!-- section checksheetday -->
            <div v-if="currentSection === 'checksheetday'" class="tab-pane fade" id="list-checksheetday" role="tabpanel" aria-labelledby="list-checksheetday-list">

              <div>
                <div class="overflow-x-auto">
                  <table class="table-auto border-collapse border border-black w-full min-w-full text-[10px] md:text-xs">
                    <thead class="bg-gray-300 text-black">
                      <tr>
                        <th colspan="11" class="border border-black px-1 py-1 text-center bg-gray-600 font-bold text-white">{{ groups[currentGroupIndex] }}</th>
                      </tr>
                      <tr>
                        <th class="border border-black px-1 py-1 text-center bg-gray-200 font-bold text-xs">No</th>
                        <th class="border border-black px-1 py-1 text-center bg-gray-200 font-bold text-xs">Komponen</th>
                        <th class="border border-black px-1 py-1 text-center bg-gray-200 font-bold text-xs">Rujukan</th>
                        <th class="border border-black px-1 py-1 text-center bg-gray-200 font-bold text-xs">Cek</th>
                        <th class="border border-black px-1 py-1 text-center bg-gray-200 font-bold text-xs">Tambah</th>
                        <th class="border border-black px-1 py-1 text-center bg-gray-200 font-bold text-xs">Service</th>
                        <th class="border border-black px-1 py-1 text-center bg-gray-200 font-bold text-xs">Ganti</th>
                        <th class="border border-black px-1 py-1 text-center bg-gray-200 font-bold text-xs">Nilai Rujukan</th>
                        <th class="border border-black px-1 py-1 text-center bg-gray-200 font-bold text-xs">Realisasi</th>
                        <!-- <th class="border border-black px-1 py-1 text-center bg-gray-200 font-bold text-xs">Kn/Dpn</th> -->
                        <th class="border border-black px-1 py-1 text-center bg-gray-200 font-bold text-xs">Sat.</th>
                        <th class="border border-black px-1 py-1 text-center bg-gray-200 font-bold text-xs">Ket.</th>
                      </tr>
                    </thead>

                    <tbody>
                      <tr v-for="(item, index) in currentGroupResults" :key="index">
                        <td class="border border-black px-2 py-1 text-center text-xs">{{ item.urutan }}</td>
                        <td class="border border-black px-2 py-1 text-left text-xs">{{ item.komponen }}</td>
                        <td class="border border-black px-2 py-1 text-xs">{{ item.rujukan }}</td>
                        <td class="border border-black px-2 py-1 text-center">
                          <input type="checkbox" :checked="item.cek == 1" @change="toggleResult(item, 'cek')" :disabled="isCheckboxDisabled"/>
                        </td>
                        <td class="border border-black px-2 py-1 text-center">
                          <input type="checkbox" :checked="item.tambahan == 1" @change="toggleResult(item, 'tambahan')" :disabled="isCheckboxDisabled"/>
                        </td>
                        <td class="border border-black px-2 py-1 text-center">
                          <input type="checkbox" :checked="item.service == 1" @change="toggleResult(item, 'service')" :disabled="isCheckboxDisabled"/>
                        </td>
                        <td class="border border-black px-2 py-1 text-center">
                          <input type="checkbox" :checked="item.ganti == 1" @change="toggleResult(item, 'ganti')" :disabled="isCheckboxDisabled"/>
                        </td>
                        <td class="border border-black px-2 py-1 text-center">{{ item.nilai_rujukan }}</td>
                        <td class="border border-black p-0 m-0 relative">
                          <input
                            v-model="item.kiri_depan"
                            type="text"
                            placeholder="...."
                            class="absolute inset-0 w-full h-full border-none focus:ring-0 text-center text-[10px] p-0 m-0"
                            @change="saveTextField(item)" :disabled="isTextFieldDisabled"/>
                        </td>
                        <!-- <td class="border border-black p-0 m-0 relative">
                          <input
                            v-model="item.kanan_depan"
                            type="text"
                            placeholder="...."
                            class="absolute inset-0 w-full h-full border-none focus:ring-0 text-center text-[10px] p-0 m-0"
                            @change="saveTextField(item)" :disabled="isTextFieldDisabled"/>
                        </td> -->
                        <td class="border border-black px-2 py-1 text-center text-[10px] p-0 m-0">{{ item.satuan }}</td>
                        <td class="border border-black p-0 m-0 relative">
                          <input
                            v-model="item.keterangan"
                            type="text"
                            placeholder="...."
                            @change="saveTextField(item)" :disabled="isTextFieldDisabled"
                            class="absolute inset-0 w-full h-full border-none focus:ring-0 text-center text-[10px] p-0 m-0"
                          />
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <div class="flex justify-between mt-4">
                    <Button v-if="!isFirstGroup" class="bg-gray-600 text-white px-4 py-1 rounded disabled:opacity-50 text-xs" @click="prevGroup">   ← Back </Button>
                    <div v-else></div> 
                    <Button v-if="!isLastGroup" class="bg-blue-600 text-white px-4 py-1 rounded disabled:opacity-50 text-xs" @click="nextGroup"> Next → </Button>
                    <Button v-if="isLastGroup && isDailyCheckCompleted" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-1 rounded text-xs shadow-lg animate-bounce" @click.prevent="fetch('warmingup', report)"> 
                        Next Warming Up 
                    </Button>
                    <!-- <div v-if="canChangeMode && isLastGroup && !props.report?.operator_at3" class="flex space-x-3">
                        <Button 
                            class="bg-orange-600 hover:bg-orange-700 text-white px-5 py-1 rounded text-xs shadow-md"
                            @click.prevent="setMode('working')">
                            Working >>
                        </Button>

                        <Button 
                            class="bg-red-600 hover:bg-red-700 text-white px-5 py-1 rounded text-xs shadow-md"
                            @click.prevent="setMode('warmingup')">
                            WarmingUp >>
                        </Button>
                    </div> -->
                  </div>
                  <br>
                </div>
                
                  <div class="d-flex justify-content-end mt-3">
                      <!-- <Button v-if="!report.checksheetday?.checksheetworkresult?.id" class="bg-green-700 hover:bg-green-900 px-4 py-1 rounded float-right mr-2 text-xs" @click.prevent="submitchecksheetworkresult()">Simpan</Button> -->
                      <!-- <Button v-if="report.checksheetday?.checksheetworkresult?.id" class="bg-blue-700 hover:bg-blue-900 px-4 py-1 rounded float-right mr-2 text-xs" @click.prevent="updatechecksheetworkresult()">Edit</Button> -->
                      <!-- <Button v-if="canApprove" class="bg-blue-700 hover:bg-blue-900 float-right mr-2 text-xs" @click.prevent="approvechecksheetworkresult()">Approve</Button> -->
                      <!-- <Button v-if="canChangeMode" class="bg-orange-600 hover:bg-orange-800 float-right mr-2 text-xs" @click.prevent="setMode('working')">Mode Working >></Button>
                      <Button v-if="canChangeMode" class="bg-red-600 hover:bg-red-800 float-right mr-2 text-xs" @click.prevent="setMode('warmingup')"> Mode Warming Up >></Button> -->
                      <!-- <Button v-if="report.checksheetday?.id || currentStep === 2" class="bg-gray-700 hover:bg-gray-900 px-4 py-1 rounded text-xs " @click="currentStep = 2" > ← Kembali</Button> -->
                  </div>
                </div>
						</div>
            <!-- section checksheetday -->

            <!-- section warmingup -->
            <div v-if="currentSection === 'warmingup'" class="tab-pane fade" id="list-warmingup" role="tabpanel" aria-labelledby="list-warmingup-list">
							<div v-if="showForm1" class="grid grid-cols-1 gap-4 mb-4 md:grid-cols-2">

                <div class="flex flex-col items-start space-y-1">
                  <label for="waktu_stop_engine" class="text-[11px] font-bold text-gray-800 uppercase tracking-wider ml-1">
                    {{ __('Stop Engine') }}
                  </label>

                  <Input
                    v-model="form3.waktu_stop_engine"
                    :placeholder="__('Stop Engine')"
                    type="time"
                    class="text-xs"
                    required
                  />

                  <InputError :error="form3.errors.waktu_stop_engine"/>
                </div>

                <!-- <div class="flex flex-col items-start space-y-1">
                  <label for="jam_traveling_akhir" class="text-[11px] font-bold text-gray-800 uppercase tracking-wider ml-1">
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
                </div> -->

                <div class="flex flex-col items-start space-y-1">
                  <label for="jam_kerja_akhir" class="text-[11px] font-bold text-gray-800 uppercase tracking-wider ml-1">
                    {{ __('Akhir Jam Kerja Operator') }}
                  </label>

                  <Input
                    v-model="form3.jam_kerja_akhir"
                    :placeholder="__('Akhir Jam Kerja Operator')"
                    type="time"
                    class="text-xs"
                    required
                  />

                  <InputError :error="form3.errors.jam_kerja_akhir"/>
                </div>

                <div class="flex flex-col items-start space-y-1">
                  <label for="jam_mesin_akhir" class="text-[11px] font-bold text-gray-800 uppercase tracking-wider ml-1">
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
                  <label for="jam_generator_akhir" class="text-[11px] font-bold text-gray-800 uppercase tracking-wider ml-1">
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
                  <label for="counter_tamping_akhir" class="text-[11px] font-bold text-gray-800 uppercase tracking-wider ml-1">
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
                  <label for="oddometer_akhir" class="text-[11px] font-bold text-gray-800 uppercase tracking-wider ml-1">
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
                  <label for="hsd_akhir_kerja" class="text-[11px] font-bold text-gray-800 uppercase tracking-wider ml-1">
                    {{ __('HSD Akhir Warming Up') }}
                  </label>

                  <Input
                    v-model="form3.hsd_akhir_kerja"
                    :placeholder="__('HSD Akhir Warming Up')"
                    type="number"
                    class="text-xs"
                    required
                  />

                  <InputError :error="form3.errors.hsd_akhir_kerja"/>
                </div>

                <div class="flex flex-col items-start space-y-1">
                  <label for="satuan" class="text-[11px] font-bold text-gray-800 uppercase tracking-wider ml-1">
                    {{ __('Satuan') }}
                  </label>

                  <Input
                    v-model="form3.satuan"
                    :placeholder="__('Satuan')"
                    type="text"
                    class="text-xs"
                    required
                  />

                  <InputError :error="form3.errors.satuan"/>
                </div>

                <!-- <div class="flex flex-col items-start space-y-1">
                  <label for="operator_by1" class="text-[11px] font-bold text-gray-800 uppercase tracking-wider ml-1">
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
                  <label for="operator_by2" class="text-[11px] font-bold text-gray-800 uppercase tracking-wider ml-1">
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
                  <label for="operator_by3" class="text-[11px] font-bold text-gray-800 uppercase tracking-wider ml-1">
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
                  <label for="approved_by" class="text-[11px] font-bold text-gray-800 uppercase tracking-wider ml-1">
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
                  <label for="approved_by1" class="text-[11px] font-bold text-gray-800 uppercase tracking-wider ml-1">
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
                  <label for="note" class="text-[11px] font-bold text-gray-800 uppercase tracking-wider ml-1">
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
              <div class="d-flex justify-content-end mt-3">
                <Button v-if="!report.warmingup?.id" class="bg-green-700 hover:bg-green-900 px-4 py-1 rounded float-right mr-2 text-xs text-white" @click.prevent="submitwarmingup()">Simpan</Button>
                <Button v-if="showForm1 && report.warmingup?.id" class="bg-blue-600 hover:bg-blue-800 px-4 py-1 rounded float-right mr-2 text-xs text-white" @click.prevent="showForm1 = false"> Next →</Button>
                <!-- <Button v-if="!report.warmingup?.id" class="bg-blue-700 hover:bg-blue-900 px-4 py-1 rounded float-right mr-2 text-xs" @click.prevent="submitwarmingup('working')">Next Working</Button>
                <Button v-if="!report.warmingup?.id" class="bg-green-700 hover:bg-green-900 px-4 py-1 rounded float-right mr-2 text-xs" @click.prevent="submitwarmingup('warmingup')">Selesai Warming Up</Button> -->
                <!-- <Button v-else class="bg-blue-700 hover:bg-blue-900 px-4 py-1 rounded float-right mr-2 text-xs" @click.prevent="updatewarmingup()">Edit</Button> -->
              </div>

              <div v-if="!showForm1" class="max-w-4xl mx-auto p-2 md:p-4 space-y-6">
                <section class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                  <div class="bg-gray-50 px-4 py-3 border-b border-gray-100">
                      <h3 class="font-bold text-gray-800 flex items-center text-sm md:text-base">
                          <span class="bg-sky-500 text-white w-6 h-6 rounded-full flex-shrink-0 flex items-center justify-center text-xs mr-2">1</span>
                          Data Opname Resor Jalan Rel (Awal)
                      </h3>
                  </div>

                  <div class="p-3 md:p-4 space-y-3">
                      <div class="group p-3 md:p-4 border border-gray-200 rounded-xl hover:border-sky-400 hover:shadow-md transition-all duration-200 bg-white">
                          <div class="flex flex-row items-center justify-between gap-2">
                              <label class="font-semibold text-gray-700 text-[11px] md:text-sm flex-1">a. IP 2 (Lurusan)</label>
                              <div class="flex items-center space-x-3 flex-shrink-0">
                                  <label class="inline-flex items-center cursor-pointer">
                                      <input type="checkbox" v-model="form7.ada" true-value="1" false-value="0" class="w-3.5 h-3.5 rounded border-gray-300 text-sky-600 focus:ring-sky-500">
                                      <span class="ml-1.5 text-[11px] md:text-sm whitespace-nowrap" :class="form7.ada === '1' ? 'text-sky-600 font-bold' : 'text-gray-500'">Ada</span>
                                  </label>
                                  <label class="inline-flex items-center cursor-pointer">
                                      <input type="checkbox" v-model="form7.tidak" true-value="1" false-value="0" class="w-3.5 h-3.5 rounded border-gray-300 text-red-600 focus:ring-red-500">
                                      <span class="ml-1.5 text-[11px] md:text-sm whitespace-nowrap" :class="form7.tidak === '1' ? 'text-red-600 font-bold' : 'text-gray-500'">Tidak ada</span>
                                  </label>
                              </div>
                          </div>
                          <div class="mt-3 pt-3 border-t border-gray-50">
                              <AttachmentInline :model="mglurusanawal ?? {}" type="MgLurusanAwal" redaction="Lampiran (IP 2 Lurusan)" :attachments="mglurusanawal_attachments" />
                          </div>
                      </div>

                      <div class="group p-3 md:p-4 border border-gray-200 rounded-xl hover:border-sky-400 transition-all bg-white">
                          <div class="flex flex-row items-center justify-between gap-2">
                              <label class="font-semibold text-gray-700 text-[11px] md:text-sm flex-1">b. IG 2 (Lengkungan)</label>
                              <div class="flex items-center space-x-3 flex-shrink-0">
                                  <label class="inline-flex items-center cursor-pointer">
                                      <input type="checkbox" v-model="form8.ada" true-value="1" false-value="0" class="w-3.5 h-3.5 rounded border-gray-300 text-sky-600">
                                      <span class="ml-1.5 text-[11px] md:text-sm whitespace-nowrap" :class="form8.ada === '1' ? 'text-sky-600 font-bold' : 'text-gray-500'">Ada</span>
                                  </label>
                                  <label class="inline-flex items-center cursor-pointer">
                                      <input type="checkbox" v-model="form8.tidak" true-value="1" false-value="0" class="w-3.5 h-3.5 rounded border-gray-300 text-red-600">
                                      <span class="ml-1.5 text-[11px] md:text-sm whitespace-nowrap" :class="form8.tidak === '1' ? 'text-red-600 font-bold' : 'text-gray-500'">Tidak ada</span>
                                  </label>
                              </div>
                          </div>
                          <div class="mt-3 pt-3 border-t border-gray-50">
                              <AttachmentInline :model="mglengkunganawal ?? {}" type="MgLengkunganAwal" redaction="Lampiran (IG 2 Lengkungan)" :attachments="mglengkunganawal_attachments" />
                          </div>
                      </div>

                      <div class="group p-3 md:p-4 border border-gray-200 rounded-xl hover:border-sky-400 transition-all bg-white">
                          <div class="flex flex-row items-center justify-between gap-2">
                              <label class="font-semibold text-gray-700 text-[11px] md:text-sm flex-1">b. IG 3 (Wesel)</label>
                              <div class="flex items-center space-x-3 flex-shrink-0">
                                  <label class="inline-flex items-center cursor-pointer">
                                      <input type="checkbox" v-model="form9.ada" true-value="1" false-value="0" class="w-3.5 h-3.5 rounded border-gray-300 text-sky-600">
                                      <span class="ml-1.5 text-[11px] md:text-sm whitespace-nowrap" :class="form9.ada === '1' ? 'text-sky-600 font-bold' : 'text-gray-500'">Ada</span>
                                  </label>
                                  <label class="inline-flex items-center cursor-pointer">
                                      <input type="checkbox" v-model="form9.tidak" true-value="1" false-value="0" class="w-3.5 h-3.5 rounded border-gray-300 text-red-600">
                                      <span class="ml-1.5 text-[11px] md:text-sm whitespace-nowrap" :class="form9.tidak === '1' ? 'text-red-600 font-bold' : 'text-gray-500'">Tidak ada</span>
                                  </label>
                              </div>
                          </div>
                          <div class="mt-3 pt-3 border-t border-gray-50">
                              <AttachmentInline :model="mgweselawal ?? {}" type="MgWeselAwal" redaction="Lampiran (IG 3 Wesel)" :attachments="mgweselawal_attachments" />
                          </div>
                      </div>
                  </div>
                </section>

                <section class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                  <div class="bg-gray-50 px-4 py-3 border-b border-gray-100">
                      <h3 class="font-bold text-gray-800 flex items-center text-sm md:text-base">
                          <span class="bg-sky-500 text-white w-6 h-6 rounded-full flex-shrink-0 flex items-center justify-center text-xs mr-2">2</span>
                          Data Pemeriksaan Silang
                      </h3>
                  </div>
                  <div class="p-3 md:p-4 space-y-3">
                      <div class="group p-3 md:p-4 border border-gray-200 rounded-xl hover:border-sky-400 bg-white transition-all">
                          <div class="flex flex-row items-center justify-between gap-2">
                              <label class="font-semibold text-gray-700 text-[11px] md:text-sm flex-1">a. KPJR</label>
                              <div class="flex items-center space-x-3 flex-shrink-0">
                                  <label class="inline-flex items-center cursor-pointer">
                                      <input type="checkbox" v-model="form10.ada" true-value="1" false-value="0" class="w-3.5 h-3.5 rounded border-gray-300 text-sky-600">
                                      <span class="ml-1.5 text-[11px] md:text-sm whitespace-nowrap" :class="form10.ada === '1' ? 'text-sky-600 font-bold' : 'text-gray-500'">Ada</span>
                                  </label>
                                  <label class="inline-flex items-center cursor-pointer">
                                      <input type="checkbox" v-model="form10.tidak" true-value="1" false-value="0" class="w-3.5 h-3.5 rounded border-gray-300 text-red-600">
                                      <span class="ml-1.5 text-[11px] md:text-sm whitespace-nowrap" :class="form10.tidak === '1' ? 'text-red-600 font-bold' : 'text-gray-500'">Tidak ada</span>
                                  </label>
                              </div>
                          </div>
                          <div class="mt-3 pt-3 border-t border-gray-50">
                              <AttachmentInline :model="pemeriksaansilangkpjr ?? {}" type="PemeriksaanSilangKpjr" redaction="Lampiran (Pemeriksaan Silang KPJR)" :attachments="pemeriksaansilangkpjr_attachments" />
                          </div>
                      </div>
                  </div>
                  <div class="p-3 md:p-4 space-y-3">
                      <div class="group p-3 md:p-4 border border-gray-200 rounded-xl hover:border-sky-400 bg-white transition-all">
                          <div class="flex flex-row items-center justify-between gap-2">
                              <label class="font-semibold text-gray-700 text-[11px] md:text-sm flex-1">b. Lahan</label>
                              <div class="flex items-center space-x-3 flex-shrink-0">
                                  <label class="inline-flex items-center cursor-pointer">
                                      <input type="checkbox" v-model="form11.ada" true-value="1" false-value="0" class="w-3.5 h-3.5 rounded border-gray-300 text-sky-600">
                                      <span class="ml-1.5 text-[11px] md:text-sm whitespace-nowrap" :class="form11.ada === '1' ? 'text-sky-600 font-bold' : 'text-gray-500'">Ada</span>
                                  </label>
                                  <label class="inline-flex items-center cursor-pointer">
                                      <input type="checkbox" v-model="form11.tidak" true-value="1" false-value="0" class="w-3.5 h-3.5 rounded border-gray-300 text-red-600">
                                      <span class="ml-1.5 text-[11px] md:text-sm whitespace-nowrap" :class="form11.tidak === '1' ? 'text-red-600 font-bold' : 'text-gray-500'">Tidak ada</span>
                                  </label>
                              </div>
                          </div>
                          <div class="mt-3 pt-3 border-t border-gray-50">
                              <AttachmentInline :model="pemeriksaansilanglahan ?? {}" type="PemeriksaanSilangLahan" redaction="Lampiran (Pemeriksaan Silang Lahan)" :attachments="pemeriksaansilanglahan_attachments" />
                          </div>
                      </div>
                  </div>
                </section>

                <p v-if="warmingup?.operator_at1" class="text-xs font-bold text-blue-600">Operator 1 telah menyetujui pada tanggal : {{ formatDate(warmingup?.operator_at1)}}</p>
                <p v-if="warmingup?.operator_at2" class="text-xs font-bold text-blue-600">Operator 2 telah menyetujui pada tanggal : {{ formatDate(warmingup?.operator_at2)}}</p>
                <p v-if="warmingup?.operator_at3" class="text-xs font-bold text-blue-600">Operator 3 telah menyetujui pada tanggal : {{ formatDate(warmingup?.operator_at3)}}</p>

                <div class="d-flex justify-content-end mt-3">
                  <Button @click="showForm1 = true" class="bg-gray-700 hover:bg-gray-900 px-4 py-1 rounded float-left mr-2 text-xs text-white">
                    ← Kembali 
                  </Button>
                  <Button v-if="pemeriksaansilanglahan?.ada !== '1' && !report.mode" type="button" @click="submitForms" class="bg-green-700 hover:bg-green-900 px-4 py-1 rounded float-right mr-2 text-xs text-white">
                      Simpan
                  </Button>
                  <Button v-if="pemeriksaansilanglahan?.ada === '1' && !report.mode" class="bg-blue-700 hover:bg-blue-900 px-4 py-1 rounded float-right mr-2 text-xs text-white" @click.prevent="setMode('working')">Next Working</Button>
                  <Button v-if="pemeriksaansilanglahan?.ada === '1' && !report.mode" class="bg-green-700 hover:bg-green-900 px-4 py-1 rounded float-right mr-2 text-xs text-white" @click.prevent="setMode('warmingup')">Selesai Warming Up</Button>
                  <!-- <Button v-else class="bg-blue-700 hover:bg-blue-900 px-4 py-1 rounded float-right mr-2 text-xs" @click.prevent="updatewarmingup()">Edit</Button> -->
                  <Button v-if="canApproveWarmingUp && !warmingup?.operator_at1" class="bg-blue-700 hover:bg-blue-900 float-right mr-2 text-xs" @click.prevent="approvewarmingup(1)">Approve 1</Button>
                  <Button v-if="canApproveWarmingUp && warmingup?.operator_at1 && !warmingup?.operator_at2" class="bg-blue-700 hover:bg-blue-900 float-right mr-2 text-xs" @click.prevent="approvewarmingup(2)">Approve 2</Button>
                  <Button v-if="canApproveWarmingUp && warmingup?.operator_at1 && warmingup?.operator_at2 && !warmingup?.operator_at3" class="bg-blue-700 hover:bg-blue-900 float-right mr-2 text-xs" @click.prevent="approvewarmingup(3)">Approve 3</Button>
                </div>
              </div>
						</div>
            <!-- section warmingup -->

            <!-- section upload -->
					  <div v-if="currentSection === 'upload'" class="tab-pane fade show active" id="list-upload" role="tabpanel" aria-labelledby="list-upload-list">

              <div class="flex flex-col space-y-4 p-1">

                <div class="flex flex-col items-start space-y-1">
                    <label for="date" class="text-[11px] font-bold text-gray-800 uppercase tracking-wider ml-1">
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

              <div v-if="currentStep1 === 1" class="space-y-6">
                <div class="flex items-center space-x-2 pb-2 border-b-2 border-blue-600">
                  <div class="bg-blue-600 text-white p-1 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                  </div>
                  <h2 class="font-black text-sm text-gray-800 uppercase tracking-tighter">A. Data Pekerjaan</h2>
                </div>

                <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm space-y-4">
                  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="flex flex-col space-y-1.5">
                      <label class="text-[10px] font-bold text-gray-800 uppercase tracking-widest ml-1">Wilayah Resor</label>
                      <Input v-model="form4.wilayah" :placeholder="__('Contoh: Resor 1.1')" type="text" class="modern-input" />
                      <InputError :error="form4.errors.wilayah" />
                    </div>

                    <div class="flex flex-col space-y-1.5">
                      <label class="text-[10px] font-bold text-gray-800 uppercase tracking-widest ml-1">Petak Jalan</label>
                      <Input v-model="form4.petak_jalan" :placeholder="__('Contoh: BDG - PDL')" type="text" class="modern-input" />
                      <InputError :error="form4.errors.petak_jalan" />
                    </div>

                    <div class="flex flex-col space-y-1.5">
                      <label class="text-[10px] font-bold text-gray-800 uppercase tracking-widest ml-1">Lokasi Stabling</label>
                      <Input v-model="form4.lokasi_stabling_awal" :placeholder="__('Contoh: Stasiun BDG')" type="text" class="modern-input" />
                      <InputError :error="form4.errors.lokasi_stabling_awal" />
                    </div>
                  </div>
                </div>

                <div class="bg-gray-50/50 p-4 rounded-xl border border-gray-200 shadow-inner">
                  <div class="flex items-center justify-between mb-4">
                    <span class="text-[11px] font-black text-blue-700 uppercase tracking-wider">Data Lurusan</span>
                    <div class="h-px flex-grow mx-4 bg-gray-200"></div>
                  </div>

                  <div class="space-y-3">
                    <div class="hidden md:grid grid-cols-12 gap-2 px-1 text-[9px] font-bold text-gray-800 uppercase">
                      <div class="col-span-3 text-center">Km/Hm Awal</div>
                      <div class="col-span-1 text-center">S/D</div>
                      <div class="col-span-3 text-center">Km/Hm Akhir</div>
                      <div class="col-span-2 text-center">Keterangan</div>
                      <div class="col-span-3 text-center">Jumlah (M'sp)</div>
                    </div>

                    <div v-for="i in [1, 2, 3]" :key="'lokasi-'+i" class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center bg-white p-2 rounded-lg border border-gray-100 shadow-sm group hover:border-blue-300 transition-colors">
                      <div class="col-span-3">
                        <Input v-model="form4['lokasi_awal'+i]" type="number" @input="handleInput('lokasi_awal'+i, $event)" placeholder="Km/Hm Awal" class="modern-input-sub" />
                      </div>
                      <div class="col-span-1 text-center font-bold text-gray-300 text-xs">to</div>
                      <div class="col-span-3">
                        <Input v-model="form4['lokasi_akhir'+i]" type="number" @input="handleInput('lokasi_awal'+i, $event)" placeholder="Km/Hm Akhir" class="modern-input-sub" />
                      </div>
                      <div class="col-span-2">
                        <select v-model="form4['hu_hi'+i]" class="modern-select-sub">
                          <option value="" disabled>Pilih</option>
                          <option value="Hulu">Hulu</option>
                          <option value="Hilir">Hilir</option>
                          <option value="Tunggal">Tunggal</option>
                          <option value="Emplasemen">Emplasemen</option>
                        </select>
                      </div>
                      <div class="col-span-3 flex items-center space-x-2">
                        <Input v-model="form4['jumlah'+i]" placeholder="0" class="modern-input-sub text-right font-mono text-blue-600" />
                        <span class="text-[9px] font-bold text-gray-800">M'SP</span>
                      </div>
                    </div>

                    <div class="flex justify-end pt-2">
                      <div class="bg-blue-600 px-4 py-2 rounded-lg shadow-md flex items-center space-x-4">
                        <span class="text-[10px] font-bold text-blue-100 uppercase">Total Jarak</span>
                        <div class="flex items-baseline space-x-1">
                          <input v-model="form4.total_distance" readonly class="bg-transparent text-white font-black text-sm w-20 text-right outline-none" />
                          <span class="text-[10px] text-blue-200 font-bold">M'SP</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="bg-gray-50/50 p-4 rounded-xl border border-gray-200 shadow-inner">
                  <div class="flex items-center justify-between mb-4">
                    <span class="text-[11px] font-black text-emerald-700 uppercase tracking-wider">Data Wesel</span>
                    <div class="h-px flex-grow mx-4 bg-gray-200"></div>
                  </div>

                  <div class="space-y-3">
                    <div class="hidden md:grid grid-cols-12 gap-2 px-2 text-[9px] font-bold text-gray-800 uppercase">
                      <div class="col-span-3 text-center">No. Wesel</div>
                      <div class="col-span-3 text-center">Lokasi Km/Hm</div>
                      <div class="col-span-2 text-center">Keterangan</div>
                      <div class="col-span-3 text-center">Jumlah (Unit)</div>
                    </div>

                    <div v-for="(i, index) in [1, 2, 3]" :key="'wesel-'+i" class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center bg-white p-2 rounded-lg border border-gray-100 shadow-sm hover:border-emerald-300 transition-colors">
                      <div class="col-span-3">
                        <Input v-model="form4['no_wesel'+i]" placeholder="No. Wesel" class="modern-input-sub border-l-4 border-l-emerald-400" />
                      </div>
                      <!-- <div class="col-span-1 text-center font-bold text-gray-300 text-xs">Km/Hm</div> -->
                      <div class="col-span-3">
                        <Input v-model="form4['km_hm'+(index+1)]" placeholder="Lokasi Km/Hm" class="modern-input-sub" />
                      </div>
                      <div class="col-span-2">
                        <select v-model="form4['hu_hi'+(i+3)]" class="modern-select-sub">
                          <option value="" disabled>Pilih</option>
                          <option value="Hulu">Hulu</option>
                          <option value="Hilir">Hilir</option>
                          <option value="Tunggal">Tunggal</option>
                          <option value="Emplasemen">Emplasemen</option>
                        </select>
                      </div>
                      <div class="col-span-3 flex items-center space-x-2">
                        <Input v-model="form4['jumlah_wesel'+i]" placeholder="0" class="modern-input-sub text-right font-mono text-emerald-600" disabled/>
                        <span class="text-[9px] font-bold text-gray-800">UNIT</span>
                      </div>
                    </div>

                    <div class="flex justify-end pt-2">
                      <div class="bg-emerald-600 px-4 py-2 rounded-lg shadow-md flex items-center space-x-4">
                        <span class="text-[10px] font-bold text-emerald-100 uppercase">Total Wesel</span>
                        <div class="flex items-baseline space-x-1">
                          <input v-model="form4.total_wesel" readonly class="bg-transparent text-white font-black text-sm w-16 text-right outline-none" />
                          <span class="text-[10px] text-emerald-200 font-bold">UNIT</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="bg-gray-50/50 p-4 rounded-xl border border-gray-200 shadow-inner">
                  <div class="flex items-center justify-between mb-4">
                    <span class="text-[11px] font-black text-yellow-700 uppercase tracking-wider">Data Lengkung</span>
                    <div class="h-px flex-grow mx-4 bg-gray-200"></div>
                  </div>

                  <div class="space-y-3">
                    <div class="hidden md:grid grid-cols-12 gap-2 px-2 text-[9px] font-bold text-gray-800 uppercase">
                      <div class="col-span-2 text-center">No. Lengkung</div>
                      <div class="col-span-2 text-center">Radius</div>
                      <div class="col-span-3 text-center">Lokasi Km/Hm</div>
                      <div class="col-span-2 text-center">Keterangan</div>
                      <div class="col-span-3 text-center">Jumlah (M'SP)</div>
                    </div>

                    <div v-for="(i, index) in [1, 2, 3]" :key="'lengkung-'+i" 
                        class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center bg-white p-2 rounded-lg border border-gray-100 shadow-sm hover:border-yellow-300 transition-colors">
                      
                      <div class="col-span-2">
                        <Input v-model="form4['no_lengkung'+i]" placeholder="No. Lengkung" class="modern-input-sub border-l-4 border-l-yellow-400" />
                      </div>

                      <div class="col-span-2 flex items-center space-x-1">
                        <!-- <span class="md:hidden text-[10px] font-bold text-gray-400">R:</span> -->
                        <Input v-model="form4['radius'+i]" placeholder="Radius" class="modern-input-sub" />
                      </div>

                      <div class="col-span-3 flex items-center space-x-1">
                        <!-- <span class="md:hidden text-[10px] font-bold text-gray-400">Km:</span> -->
                        <Input v-model="form4['km_hm_lengkung'+(i)]" placeholder="Lokasi Km/Hm" class="modern-input-sub" />
                      </div>

                      <div class="col-span-2">
                        <select v-model="form4['hu_hi'+(i+6)]" class="modern-select-sub">
                          <option value="" disabled>Pilih</option>
                          <option value="Hulu">Hulu</option>
                          <option value="Hilir">Hilir</option>
                          <option value="Tunggal">Tunggal</option>
                          <option value="Emplasemen">Emplasemen</option>
                        </select>
                      </div>

                      <div class="col-span-3 flex items-center space-x-2">
                        <Input v-model="form4['jumlah_lengkung'+i]" placeholder="0" class="modern-input-sub text-right font-mono text-yellow-600 bg-yellow-50/30" />
                        <span class="text-[9px] font-bold text-gray-800">M'SP</span>
                      </div>
                    </div>

                    <div class="flex justify-end pt-2">
                      <div class="bg-yellow-600 px-4 py-2 rounded-lg shadow-md flex items-center space-x-4">
                        <span class="text-[10px] font-bold text-yellow-100 uppercase">Total Lengkung</span>
                        <div class="flex items-baseline space-x-1">
                          <input v-model="form4.total_lengkung" readonly class="bg-transparent text-white font-black text-sm w-20 text-right outline-none" />
                          <span class="text-[10px] text-yellow-200 font-bold">M'SP</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="flex justify-end pt-4">
                  <button 
                    @click="currentStep1 = 2" 
                    class="group bg-blue-700 hover:bg-blue-800 text-white pl-6 pr-4 py-2.5 rounded-xl shadow-lg shadow-blue-200 flex items-center space-x-3 transition-all active:scale-95"
                  >
                    <span class="text-xs font-bold uppercase tracking-widest">Lanjutkan ke Step 2</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                  </button>
                </div>
              </div>

              <div v-if="currentStep1 === 2" class="space-y-6 animate-fade-in">
                <div class="flex items-center space-x-2 pb-2 border-b-2 border-emerald-600">
                  <div class="bg-emerald-600 text-white p-1 rounded shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                  </div>
                  <h2 class="font-black text-sm text-gray-800 uppercase tracking-tighter">B. Data Operasi Mesin</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  
                  <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm space-y-4 hover:shadow-md transition-shadow">
                    <h3 class="text-[10px] font-black text-emerald-600 uppercase tracking-widest border-b pb-2">Log Waktu & Traveling</h3>
                    
                    <div class="grid grid-cols-1 gap-4">
                      <div class="flex flex-col space-y-1.5">
                        <label class="text-[10px] font-bold text-gray-800 uppercase tracking-widest ml-1">Waktu Stop Engine</label>
                        <div class="relative">
                          <Input v-model="form4.waktu_stop_engine" type="time" class="modern-input pl-8" />
                          <div class="absolute left-2.5 top-2.5 text-gray-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                          </div>
                        </div>
                        <InputError :error="form4.errors.waktu_stop_engine" />
                      </div>

                      <div class="flex flex-col space-y-1.5">
                        <label class="text-[10px] font-bold text-gray-800 uppercase tracking-widest ml-1">Akhir Jam Kerja Operator</label>
                        <Input v-model="form4.jam_kerja_akhir" type="time" class="modern-input" />
                        <InputError :error="form4.errors.jam_kerja_akhir" />
                      </div>

                      <div class="flex flex-col space-y-1.5">
                        <label class="text-[10px] font-bold text-gray-800 uppercase tracking-widest ml-1">Jam Travelling Akhir</label>
                        <Input v-model="form4.jam_traveling_akhir" :placeholder="__('0.00')" type="text" class="modern-input font-mono" />
                        <InputError :error="form4.errors.jam_traveling_akhir" />
                      </div>
                    </div>
                  </div>

                  <div class="bg-gray-50/50 p-5 rounded-xl border border-gray-200 shadow-inner space-y-4">
                    <h3 class="text-[10px] font-black text-blue-600 uppercase tracking-widest border-b border-gray-200 pb-2">Log Mesin & Produksi</h3>
                    
                    <div class="grid grid-cols-1 gap-3">
                      <div class="grid grid-cols-2 gap-3">
                        <div class="flex flex-col space-y-1.5">
                          <label class="text-[10px] font-bold text-gray-800 uppercase tracking-widest ml-1">Jam Mesin Akhir</label>
                          <Input v-model="form4.jam_mesin_akhir" placeholder="0" type="text" class="modern-input-sub text-center font-bold" />
                          <InputError :error="form4.errors.jam_mesin_akhir" />
                        </div>
                        <div class="flex flex-col space-y-1.5">
                          <label class="text-[10px] font-bold text-gray-800 uppercase tracking-widest ml-1">Jam Gen Akhir</label>
                          <Input v-model="form4.jam_generator_akhir" placeholder="0" type="text" class="modern-input-sub text-center font-bold" />
                          <InputError :error="form4.errors.jam_generator_akhir" />
                        </div>
                      </div>

                      <div class="flex flex-col space-y-1.5">
                        <label class="text-[10px] font-bold text-gray-800 uppercase tracking-widest ml-1">Counter Tamping Akhir</label>
                        <div class="flex items-center">
                          <Input v-model="form4.counter_tamping_akhir" type="number" class="modern-input border-r-0 rounded-r-none flex-grow" />
                          <span class="bg-gray-100 border border-l-0 border-gray-200 px-3 py-2 rounded-r-lg text-[9px] font-bold text-gray-500 uppercase">Points</span>
                        </div>
                        <InputError :error="form4.errors.counter_tamping_akhir" />
                      </div>

                      <div class="grid grid-cols-2 gap-3">
                        <div class="flex flex-col space-y-1.5">
                          <label class="text-[10px] font-bold text-gray-800 uppercase tracking-widest ml-1">Oddometer Akhir</label>
                          <Input v-model="form4.oddometer_akhir" type="number" class="modern-input" />
                          <InputError :error="form4.errors.oddometer_akhir" />
                        </div>
                        <div class="flex flex-col space-y-1.5">
                          <label class="text-[10px] font-bold text-gray-800 uppercase tracking-widest ml-1">HSD Akhir (%)</label>
                          <div class="flex items-center">
                              <Input v-model="form4.hsd_akhir_kerja" type="number" class="modern-input border-r-0 rounded-r-none" />
                              <span class="bg-emerald-50 border border-l-0 border-emerald-200 px-2 py-2 rounded-r-lg text-emerald-600 font-bold text-xs">%</span>
                          </div>
                          <InputError :error="form4.errors.hsd_akhir_kerja" />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="flex items-center justify-between pt-6 border-t border-gray-100">
                  <button 
                    @click="currentStep1 = 1" 
                    class="flex items-center space-x-2 text-gray-500 hover:text-gray-800 transition-colors font-bold text-xs uppercase tracking-widest"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Kembali</span>
                  </button>

                  <div class="flex space-x-3">
                    <button 
                      v-if="!report.workresult?.id"
                      @click.prevent="submitworkresult()" 
                      class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-xl shadow-lg shadow-emerald-100 flex items-center space-x-2 transition-all active:scale-95"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                      </svg>
                      <span class="text-xs font-bold uppercase">Simpan Data</span>
                    </button>

                    <button 
                      v-if="report.workresult?.id"
                      @click="currentStep1 = 3" 
                      class="bg-blue-700 hover:bg-blue-800 text-white px-8 py-2.5 rounded-xl shadow-lg shadow-blue-100 flex items-center space-x-2 transition-all active:scale-95"
                    >
                      <span class="text-xs font-bold uppercase tracking-widest">Lanjut Step 3</span>
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                      </svg>
                    </button>
                  </div>
                </div>
              </div>

              <div v-if="currentStep1 === 3" class="space-y-6 animate-fade-in">
                <div class="flex items-center space-x-2 pb-2 border-b-2 border-blue-600">
                  <div class="bg-blue-600 text-white p-1 rounded shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                  </div>
                  <h2 class="font-black text-sm text-gray-800 uppercase tracking-tighter">C. Dokumen Lampiran Akhir</h2>
                </div>

                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                  <div class="bg-gray-50 px-4 py-2 border-b border-gray-100">
                    <h3 class="text-[11px] font-black text-gray-600 uppercase tracking-widest">1. Data Opname Resor Jalan Rel (Akhir)</h3>
                  </div>
                  
                  <div class="p-4 space-y-3">
                    <div v-for="(form, index) in [ {f: form13, label: 'a. IP 2 (Lurusan)', model: mglurusanakhir, type: 'MgLurusanAkhir', attach: mglurusanakhir_attachments}, 
                                                  {f: form14, label: 'b. IG 2 (Lengkung)', model: mglengkunganakhir, type: 'MgLengkunganAkhir', attach: mglengkunganakhir_attachments},
                                                  {f: form15, label: 'c. IG 3 (Wesel)', model: mgweselakhir, type: 'MgWeselAkhir', attach: mgweselakhir_attachments} ]" 
                        :key="index"
                        class="group p-3 border border-gray-100 rounded-xl hover:border-blue-200 hover:bg-blue-50/30 transition-all duration-200">
                      
                      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <span class="text-xs font-bold text-gray-700">{{ form.label }}</span>
                        
                        <div class="flex bg-gray-100 p-1 rounded-lg w-fit">
                          <label class="flex items-center px-3 py-1 rounded-md cursor-pointer transition-all"
                                :class="form.f.ada === '1' ? 'bg-white shadow-sm text-blue-600 font-bold' : 'text-gray-800 hover:text-gray-600'">
                            <input type="checkbox" v-model="form.f.ada" true-value="1" false-value="0" class="hidden">
                            <span class="text-[10px] uppercase">Ada</span>
                          </label>
                          <label class="flex items-center px-3 py-1 rounded-md cursor-pointer transition-all"
                                :class="form.f.tidak === '1' ? 'bg-white shadow-sm text-red-600 font-bold' : 'text-gray-800 hover:text-gray-600'">
                            <input type="checkbox" v-model="form.f.tidak" true-value="1" false-value="0" class="hidden">
                            <span class="text-[10px] uppercase">Tidak</span>
                          </label>
                        </div>
                      </div>

                      <div class="mt-3 border-t border-dashed border-gray-200 pt-3">
                        <AttachmentInline
                            :model="form.model ?? {}"
                            :type="form.type"
                            :redaction="`Upload File ${form.label}`"
                            :attachments="form.attach"
                          />
                      </div>
                    </div>
                  </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                  <div class="bg-gray-50 px-4 py-2 border-b border-gray-100">
                    <h3 class="text-[11px] font-black text-gray-600 uppercase tracking-widest">2. Data Perekaman (Akhir)</h3>
                  </div>
                  
                  <div class="p-4">
                    <div class="group p-3 border border-gray-100 rounded-xl hover:border-emerald-200 hover:bg-emerald-50/30 transition-all duration-200">
                      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <span class="text-xs font-bold text-gray-700">a. IP 2 (Lurusan)</span>
                        
                        <div class="flex bg-gray-100 p-1 rounded-lg w-fit">
                          <label class="flex items-center px-3 py-1 rounded-md cursor-pointer transition-all"
                                :class="form16.ada === '1' ? 'bg-white shadow-sm text-emerald-600 font-bold' : 'text-gray-800 hover:text-gray-600'">
                            <input type="checkbox" v-model="form16.ada" true-value="1" false-value="0" class="hidden">
                            <span class="text-[10px] uppercase">Ada</span>
                          </label>
                          <label class="flex items-center px-3 py-1 rounded-md cursor-pointer transition-all"
                                :class="form16.tidak === '1' ? 'bg-white shadow-sm text-red-600 font-bold' : 'text-gray-800 hover:text-gray-600'">
                            <input type="checkbox" v-model="form16.tidak" true-value="1" false-value="0" class="hidden">
                            <span class="text-[10px] uppercase">Tidak</span>
                          </label>
                        </div>
                      </div>

                      <div class="mt-3 border-t border-dashed border-gray-200 pt-3">
                        <AttachmentInline
                          :model="perekamanakhir ?? {}"
                          type="PerekamanAkhir"
                          :redaction="`Upload Hasil Perekaman Akhir`"
                          :attachments="perekamanakhir_attachments"
                        />
                      </div>
                    </div>
                  </div>
                </div>

                <div class="flex items-center justify-between pt-6 border-t border-gray-100">
                  <button 
                    @click="currentStep1 = 2" 
                    class="flex items-center space-x-2 text-gray-500 hover:text-gray-800 transition-colors font-bold text-xs uppercase tracking-widest"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Kembali</span>
                  </button>

                  <div class="flex space-x-3">
                    <button 
                      @click="submitForm" 
                      class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-xl shadow-lg shadow-emerald-100 flex items-center space-x-2 transition-all active:scale-95"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                      </svg>
                      <span class="text-xs font-bold uppercase">Simpan Form</span>
                    </button>

                    <button 
                      v-if="canApproveWorkResult"
                      @click.prevent="approveworkresult()" 
                      class="bg-blue-700 hover:bg-blue-800 text-white px-6 py-2.5 rounded-xl shadow-lg shadow-blue-100 flex items-center space-x-2 transition-all active:scale-95"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                      <span class="text-xs font-bold uppercase tracking-widest">Approve</span>
                    </button>
                  </div>
                </div>
              </div>
            </div>
            <!-- section workresult -->

            <!-- section workresultok -->
            <div v-if="currentSection === 'workresultok' && report?.mode === 'working'" class="tab-pane fade" id="list-workresultok" role="tabpanel" aria-labelledby="list-warmingup-list">
              <div class="p-5">
                <div class="mb-6">

                  <div class="bg-gray-100 px-3 py-1.5 rounded-md mb-3 border-l-4 border-gray-800">
                      <span class="text-[11px] font-black text-gray-700 uppercase">A. Informasi Umum</span>
                  </div>
                  
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-1">
                    <div v-for="(val, label) in {
                          'Hari / Tanggal': formatDateDay(report?.date || '-'),
                          'Cuaca': report?.cuaca,
                          'Awal Jam Kerja': (report?.jam_kerja_awal?.slice(0, 5) || '-') + ' WIB',
                      }" :key="label" class="flex items-center py-2 border-b border-gray-100 group">
                          <div class="w-40 text-[11px] font-bold text-gray-400 uppercase tracking-tight">{{ label }}</div>
                          <div class="text-gray-300 mr-3">:</div>
                          <div class="text-xs font-semibold text-gray-800 group-hover:text-blue-600 transition-colors capitalize">
                              {{ val || '-' }}
                          </div>
                      </div>
                    </div>
                  </div>

                  <div class="mb-6">
                    <div class="bg-gray-100 px-3 py-1.5 rounded-md mb-3 border-l-4 border-green-600">
                        <span class="text-[11px] font-black text-gray-700 uppercase">B. Data Personel</span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-1">
                        <div v-for="(val, label) in {
                            'Operator 1': `[${report?.operator1?.username || ''}] ${report?.operator1?.name || '-'}`,
                            'Operator 2': `[${report?.operator2?.username || ''}] ${report?.operator2?.name || '-'}`,
                            ...(report?.operator_by3 && report?.operator3?.name && report?.operator3?.name !== '-' ? {'Operator 3': `[${report?.operator3?.username || ''}] ${report?.operator3?.name || '-'}`} : {}),
                            ...(report?.nipp && report?.nipp !== '' ? {'Pengawal 1': `[${report?.nipp}] ${report?.nama_pengawal || '-'}`} : {}),
                            ...(report?.nipp1 && report?.nipp1 !== '' ? {'Pengawal 2': `[${report?.nipp1}] ${report?.nama_pengawal1 || '-'}`} : {}),
                        }" :key="label" class="flex items-center py-2 border-b border-gray-50 group">
                            <div class="w-40 text-[11px] font-bold text-gray-400 uppercase">{{ label }}</div>
                            <div class="text-gray-300 mr-3">:</div>
                            <div class="text-xs font-bold text-gray-700 capitalize">{{ val.toLowerCase() }}</div>
                        </div>
                    </div>
                  </div>

                  <div class="mb-6">
                    <div class="bg-gray-100 px-3 py-1.5 rounded-md mb-3 border-l-4 border-yellow-600">
                        <span class="text-[11px] font-black text-gray-700 uppercase">C. Data Spesifikasi Mesin</span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-1">
                        <div v-for="(val, label) in {
                            'Klasifikasi': report?.klasifikasi,
                            'Type': report?.type,
                            'Merk': report?.jenis_kpjr,
                            'Nomor Sarana': report?.nomor_sarana,
                            'Nomor Mesin': report?.nomor_mesin,
                        }" :key="label" class="flex items-center py-2 border-b border-gray-50">
                            <div class="w-40 text-[11px] font-bold text-gray-400 uppercase leading-none">{{ label }}</div>
                            <div class="text-gray-300 mr-3">:</div>
                            <div class="text-xs font-bold text-gray-700 capitalize">{{ val || '-' }}</div>
                        </div>
                    </div>
                  </div>

                  <div class="mb-6">
                    <div class="bg-gray-100 px-3 py-1.5 rounded-md mb-3 border-l-4 border-blue-500">
                        <span class="text-[11px] font-black text-gray-700 uppercase">D. Data Operasi Mesin (Warmingup)</span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-1">
                        <div v-for="(val, label) in {
                            'Start Engine': (report?.waktu_start_engine?.slice(0, 5) || '-') + ' WIB',
                            'Stop Engine': (report?.warmingup?.waktu_stop_engine?.slice(0, 5) || '-') + ' WIB',
                            'Jam Traveling Awal': (report?.jam_traveling_awal) + ' WIB',
                            // 'Jam Traveling Akhir': report?.warmingup?.jam_traveling_akhir,
                            'Awal Jam Kerja Op': (report?.jam_kerja_awal?.slice(0, 5) || '-') + ' WIB',
                            'Akhir Jam Kerja Op': (report?.warmingup?.jam_kerja_akhir?.slice(0, 5) || '-') + ' WIB',
                            'Jam Mesin Awal': (report?.jam_mesin_awal) + ' Hours',
                            'Jam Mesin Akhir': (report?.warmingup?.jam_mesin_akhir) + ' Hours',
                            'Jam Generator Awal': (report?.jam_generator_awal) + ' Hours',
                            'Jam Generator Akhir': (report?.warmingup?.jam_generator_akhir) + ' Hours',
                            'Counter Tamping Awal': report?.counter_tamping_awal,
                            'Counter Tamping Akhir': report?.warmingup?.counter_tamping_akhir,
                            'Oddometer Awal': report?.oddometer_awal,
                            'Oddometer Akhir': report?.warmingup?.oddometer_akhir,
                            'HSD Awal Kerja': (report?.hsd_awal_kerja || '-') + ' %',
                            'HSD Akhir Warming Up': (report?.warmingup?.hsd_akhir_kerja || '-') + ' %',
                            'Konsumsi HSD': (report?.warmingup?.konsumsi_hsd || '-') + ' Ltr',
                            'Satuan': (report?.warmingup?.satuan || '-') + ' %'
                        }" :key="label" class="flex items-center py-2 border-b border-gray-50">
                            <div class="w-40 text-[11px] font-bold text-gray-400 uppercase leading-none">{{ label }}</div>
                            <div class="text-gray-300 mr-3">:</div>
                            <div class="text-xs font-bold text-gray-700 capitalize">{{ val || '-' }}</div>
                        </div>
                    </div>
                  </div>
                  

                  <div class="mb-6">
                    <div class="bg-gray-100 px-3 py-1.5 rounded-md mb-3 border-l-4 border-pink-700">
                        <span class="text-[11px] font-black text-gray-700 uppercase">E. Data Pekerjaan</span>
                    </div>

                    <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm space-y-4">
                      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="flex flex-col space-y-1.5">
                          <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Wilayah Resor</label>
                          <Input v-model="form4.wilayah" :placeholder="__('Contoh: Resor 1.1')" disabled type="text" class="modern-input" />
                          <InputError :error="form4.errors.wilayah" />
                        </div>

                        <div class="flex flex-col space-y-1.5">
                          <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Petak Jalan</label>
                          <Input v-model="form4.petak_jalan" :placeholder="__('Contoh: BDG - PDL')" disabled type="text" class="modern-input" />
                          <InputError :error="form4.errors.petak_jalan" />
                        </div>

                        <div class="flex flex-col space-y-1.5">
                          <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Lokasi Stabling</label>
                          <Input v-model="form4.lokasi_stabling_awal" :placeholder="__('Contoh: Stasiun BDG')" disabled type="text" class="modern-input" />
                          <InputError :error="form4.errors.lokasi_stabling_awal" />
                        </div>
                      </div>
                    </div>

                    <div v-if="form4.lokasi_awal1 || form4.lokasi_awal2 || form4.lokasi_awal3" class="bg-gray-50/50 p-4 rounded-xl border border-gray-200 shadow-inner">
                      <div class="flex items-center justify-between mb-4">
                        <span class="text-[11px] font-black text-blue-700 uppercase tracking-wider">Data Lurusan</span>
                        <div class="h-px flex-grow mx-4 bg-gray-200"></div>
                      </div>

                      <div class="space-y-3">
                        <div class="hidden md:grid grid-cols-12 gap-2 px-1 text-[9px] font-bold text-gray-800 uppercase">
                          <div class="col-span-3 text-center">Km/Hm Awal</div>
                          <div class="col-span-1 text-center">S/D</div>
                          <div class="col-span-3 text-center">Km/Hm Akhir</div>
                          <div class="col-span-2 text-center">Keterangan</div>
                          <div class="col-span-3 text-center">Jumlah (M'sp)</div>
                        </div>

                        <div v-if="form4.lokasi_awal1" class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center bg-white p-2 rounded-lg border border-gray-100 shadow-sm">
                          <div class="col-span-3"><Input v-model="form4.lokasi_awal1" disabled class="modern-input-sub" /></div>
                          <div class="col-span-1 text-center font-bold text-gray-300 text-xs">to</div>
                          <div class="col-span-3"><Input v-model="form4.lokasi_akhir1" disabled class="modern-input-sub" /></div>
                          <div class="col-span-2">
                            <select v-model="form4.hu_hi1" class="modern-select-sub">
                              <option value="Hulu">Hulu</option>
                              <option value="Hilir">Hilir</option>
                              <option value="Tunggal">Tunggal</option>
                              <option value="Emplasemen">Emplasemen</option>
                            </select>
                          </div>
                          <div class="col-span-3"><Input v-model="form4.jumlah1" disabled class="modern-input-sub text-right font-mono text-blue-600" /></div>
                        </div>

                        <div v-if="form4.lokasi_awal2" class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center bg-white p-2 rounded-lg border border-gray-100 shadow-sm">
                          <div class="col-span-3"><Input v-model="form4.lokasi_awal2" disabled class="modern-input-sub" /></div>
                          <div class="col-span-1 text-center font-bold text-gray-300 text-xs">to</div>
                          <div class="col-span-3"><Input v-model="form4.lokasi_akhir2" disabled class="modern-input-sub" /></div>
                          <div class="col-span-2">
                            <select v-model="form4.hu_hi2" class="modern-select-sub">
                              <option value="Hulu">Hulu</option>
                              <option value="Hilir">Hilir</option>
                              <option value="Tunggal">Tunggal</option>
                              <option value="Emplasemen">Emplasemen</option>
                            </select>
                          </div>
                          <div class="col-span-3"><Input v-model="form4.jumlah2" disabled class="modern-input-sub text-right font-mono text-blue-600" /></div>
                        </div>

                        <div v-if="form4.lokasi_awal3" class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center bg-white p-2 rounded-lg border border-gray-100 shadow-sm">
                          <div class="col-span-3"><Input v-model="form4.lokasi_awal3" disabled class="modern-input-sub" /></div>
                          <div class="col-span-1 text-center font-bold text-gray-300 text-xs">to</div>
                          <div class="col-span-3"><Input v-model="form4.lokasi_akhir3" disabled class="modern-input-sub" /></div>
                          <div class="col-span-2">
                            <select v-model="form4.hu_hi3" class="modern-select-sub">
                              <option value="Hulu">Hulu</option>
                              <option value="Hilir">Hilir</option>
                              <option value="Tunggal">Tunggal</option>
                              <option value="Emplasemen">Emplasemen</option>
                            </select>
                          </div>
                          <div class="col-span-3"><Input v-model="form4.jumlah3" disabled class="modern-input-sub text-right font-mono text-blue-600" /></div>
                        </div>

                        <div class="flex justify-end pt-2">
                          <div class="bg-blue-600 px-4 py-2 rounded-lg shadow-md flex items-center space-x-4">
                            <span class="text-[10px] font-bold text-blue-100 uppercase">Total Jarak</span>
                            <div class="flex items-baseline space-x-1">
                              <input v-model="form4.total_distance" readonly class="bg-transparent text-white font-black text-sm w-20 text-right outline-none" />
                              <span class="text-[10px] text-blue-200 font-bold">M'SP</span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div v-if="form4.no_wesel1 || form4.no_wesel2 || form4.no_wesel3" class="bg-gray-50/50 p-4 rounded-xl border border-gray-200 shadow-inner">
                      <div class="flex items-center justify-between mb-4">
                        <span class="text-[11px] font-black text-emerald-700 uppercase tracking-wider">Data Wesel</span>
                        <div class="h-px flex-grow mx-4 bg-gray-200"></div>
                      </div>

                      <div class="space-y-3">
                        <div class="hidden md:grid grid-cols-12 gap-2 px-2 text-[9px] font-bold text-gray-800 uppercase">
                          <div class="col-span-3 text-center">No. Wesel</div>
                          <div class="col-span-3 text-center">Lokasi Km/Hm</div>
                          <div class="col-span-2 text-center">Keterangan</div>
                          <div class="col-span-3 text-center">Jumlah (Unit)</div>
                        </div>

                        <div v-if="form4.no_wesel1" class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center bg-white p-2 rounded-lg border border-gray-100 shadow-sm hover:border-emerald-300 transition-colors">
                          <div class="col-span-3">
                            <Input v-model="form4.no_wesel1" disabled placeholder="No. Wesel" class="modern-input-sub border-l-4 border-l-emerald-400" />
                          </div>
                          <!-- <div class="col-span-1 text-center font-bold text-gray-300 text-xs">Km/Hm</div> -->
                          <div class="col-span-3">
                            <Input v-model="form4.km_hm1" disabled placeholder="Lokasi Km/Hm" class="modern-input-sub" />
                          </div>
                          <div class="col-span-2">
                            <select v-model="form4.hu_hi4" class="modern-select-sub">
                              <option value="" disabled>Pilih</option>
                              <option value="Hulu">Hulu</option>
                              <option value="Hilir">Hilir</option>
                              <option value="Tunggal">Tunggal</option>
                              <option value="Emplasemen">Emplasemen</option>
                            </select>
                          </div>
                          <div class="col-span-3 flex items-center space-x-2">
                            <Input v-model="form4.jumlah_wesel1" disabled placeholder="0" class="modern-input-sub text-right font-mono text-emerald-600" />
                            <span class="text-[9px] font-bold text-gray-400">UNIT</span>
                          </div>
                        </div>

                        <div v-if="form4.no_wesel2" class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center bg-white p-2 rounded-lg border border-gray-100 shadow-sm hover:border-emerald-300 transition-colors">
                          <div class="col-span-3">
                            <Input v-model="form4.no_wesel2" disabled placeholder="No. Wesel" class="modern-input-sub border-l-4 border-l-emerald-400" />
                          </div>
                          <!-- <div class="col-span-1 text-center font-bold text-gray-300 text-xs">Km/Hm</div> -->
                          <div class="col-span-3">
                            <Input v-model="form4.km_hm2" disabled placeholder="Lokasi Km/Hm" class="modern-input-sub" />
                          </div>
                          <div class="col-span-2">
                            <select v-model="form4.hu_hi5" class="modern-select-sub">
                              <option value="" disabled>Pilih</option>
                              <option value="Hulu">Hulu</option>
                              <option value="Hilir">Hilir</option>
                              <option value="Tunggal">Tunggal</option>
                              <option value="Emplasemen">Emplasemen</option>
                            </select>
                          </div>
                          <div class="col-span-3 flex items-center space-x-2">
                            <Input v-model="form4.jumlah_wesel2" disabled placeholder="0" class="modern-input-sub text-right font-mono text-emerald-600" />
                            <span class="text-[9px] font-bold text-gray-400">UNIT</span>
                          </div>
                        </div>

                        <div v-if="form4.no_wesel3" class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center bg-white p-2 rounded-lg border border-gray-100 shadow-sm hover:border-emerald-300 transition-colors">
                          <div class="col-span-3">
                            <Input v-model="form4.no_wesel3" disabled placeholder="No. Wesel" class="modern-input-sub border-l-4 border-l-emerald-400" />
                          </div>
                          <!-- <div class="col-span-1 text-center font-bold text-gray-300 text-xs">Km/Hm</div> -->
                          <div class="col-span-3">
                            <Input v-model="form4.km_hm3" disabled placeholder="Lokasi Km/Hm" class="modern-input-sub" />
                          </div>
                          <div class="col-span-2">
                            <select v-model="form4.hu_hi6" class="modern-select-sub">
                              <option value="" disabled>Pilih</option>
                              <option value="Hulu">Hulu</option>
                              <option value="Hilir">Hilir</option>
                              <option value="Tunggal">Tunggal</option>
                              <option value="Emplasemen">Emplasemen</option>
                            </select>
                          </div>
                          <div class="col-span-3 flex items-center space-x-2">
                            <Input v-model="form4.jumlah_wesel3" disabled placeholder="0" class="modern-input-sub text-right font-mono text-emerald-600" />
                            <span class="text-[9px] font-bold text-gray-400">UNIT</span>
                          </div>
                        </div>

                        <div class="flex justify-end pt-2">
                          <div class="bg-emerald-600 px-4 py-2 rounded-lg shadow-md flex items-center space-x-4">
                            <span class="text-[10px] font-bold text-emerald-100 uppercase">Total Wesel</span>
                            <div class="flex items-baseline space-x-1">
                              <input v-model="form4.total_wesel" readonly class="bg-transparent text-white font-black text-sm w-16 text-right outline-none" />
                              <span class="text-[10px] text-emerald-200 font-bold">UNIT</span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div> 
                    
                    <div v-if="form4.no_lengkung1 || form4.no_lengkung2 || form4.no_lengkung3" class="bg-gray-50/50 p-4 rounded-xl border border-gray-200 shadow-inner">
                      <div class="flex items-center justify-between mb-4">
                        <span class="text-[11px] font-black text-yellow-700 uppercase tracking-wider">Data Lengkung</span>
                        <div class="h-px flex-grow mx-4 bg-gray-200"></div>
                      </div>

                      <div class="space-y-3">
                        <div class="hidden md:grid grid-cols-12 gap-2 px-2 text-[9px] font-bold text-gray-800 uppercase">
                          <div class="col-span-2 text-center">No. Lengkung</div>
                          <div class="col-span-2 text-center">Radius</div>
                          <div class="col-span-3 text-center">Lokasi Km/Hm</div>
                          <div class="col-span-2 text-center">Keterangan</div>
                          <div class="col-span-3 text-center">Jumlah (M'SP)</div>
                        </div>
                        <div v-if="form4.no_lengkung1" class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center bg-white p-2 rounded-lg border border-gray-100 shadow-sm hover:border-yellow-300 transition-colors">
                          <div class="col-span-2">
                            <Input v-model="form4.no_lengkung1" disabled placeholder="No. Lengkung" class="modern-input-sub border-l-4 border-l-yellow-400" />
                          </div>
                          <!-- <div class="col-span-1 text-center font-bold text-gray-300 text-xs">Radius</div> -->
                          <div class="col-span-2">
                            <Input v-model="form4.radius1" disabled placeholder="Radius" class="modern-input-sub" />
                          </div>
                          <div class="col-span-3">
                            <Input v-model="form4.km_hm_lengkung1" disabled placeholder="Km/Hm" class="modern-input-sub" />
                          </div>
                          <div class="col-span-2">
                            <select v-model="form4.hu_hi7" class="modern-select-sub">
                              <option value="" disabled>Pilih</option>
                              <option value="Hulu">Hulu</option>
                              <option value="Hilir">Hilir</option>
                              <option value="Tunggal">Tunggal</option>
                              <option value="Emplasemen">Emplasemen</option>
                            </select>
                          </div>
                          <div class="col-span-3 flex items-center space-x-2">
                            <Input v-model="form4.jumlah_lengkung1" disabled placeholder="0" class="modern-input-sub text-right font-mono text-yellow-600" />
                            <span class="text-[9px] font-bold text-gray-400">M'SP</span>
                          </div>
                        </div>

                        <div v-if="form4.no_lengkung2" class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center bg-white p-2 rounded-lg border border-gray-100 shadow-sm hover:border-yellow-300 transition-colors">
                          <div class="col-span-2">
                            <Input v-model="form4.no_lengkung2" disabled placeholder="No. Lengkung" class="modern-input-sub border-l-4 border-l-yellow-400" />
                          </div>
                          <!-- <div class="col-span-1 text-center font-bold text-gray-300 text-xs">Radius</div> -->
                          <div class="col-span-2">
                            <Input v-model="form4.radius2" disabled placeholder="Radius" class="modern-input-sub" />
                          </div>
                          <div class="col-span-3">
                            <Input v-model="form4.km_hm_lengkung2" disabled placeholder="Km/Hm" class="modern-input-sub" />
                          </div>
                          <div class="col-span-2">
                            <select v-model="form4.hu_hi8" class="modern-select-sub">
                              <option value="" disabled>Pilih</option>
                              <option value="Hulu">Hulu</option>
                              <option value="Hilir">Hilir</option>
                              <option value="Tunggal">Tunggal</option>
                              <option value="Emplasemen">Emplasemen</option>
                            </select>
                          </div>
                          <div class="col-span-3 flex items-center space-x-2">
                            <Input v-model="form4.jumlah_lengkung2" disabled placeholder="0" class="modern-input-sub text-right font-mono text-yellow-600" />
                            <span class="text-[9px] font-bold text-gray-400">M'SP</span>
                          </div>
                        </div>

                        <div v-if="form4.no_lengkung3" class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center bg-white p-2 rounded-lg border border-gray-100 shadow-sm hover:border-yellow-300 transition-colors">
                          <div class="col-span-2">
                            <Input v-model="form4.no_lengkung3" disabled placeholder="No. Lengkung" class="modern-input-sub border-l-4 border-l-yellow-400" />
                          </div>
                          <!-- <div class="col-span-1 text-center font-bold text-gray-300 text-xs">Radius</div> -->
                          <div class="col-span-2">
                            <Input v-model="form4.radius3" disabled placeholder="Radius" class="modern-input-sub" />
                          </div>
                          <div class="col-span-3">
                            <Input v-model="form4.km_hm_lengkung3" disabled placeholder="Km/Hm" class="modern-input-sub" />
                          </div>
                          <div class="col-span-2">
                            <select v-model="form4.hu_hi9" class="modern-select-sub">
                              <option value="" disabled>Pilih</option>
                              <option value="Hulu">Hulu</option>
                              <option value="Hilir">Hilir</option>
                              <option value="Tunggal">Tunggal</option>
                              <option value="Emplasemen">Emplasemen</option>
                            </select>
                          </div>
                          <div class="col-span-3 flex items-center space-x-2">
                            <Input v-model="form4.jumlah_lengkung3" disabled placeholder="0" class="modern-input-sub text-right font-mono text-yellow-600" />
                            <span class="text-[9px] font-bold text-gray-400">M'SP</span>
                          </div>
                        </div>

                        <div class="flex justify-end pt-2">
                          <div class="bg-yellow-600 px-4 py-2 rounded-lg shadow-md flex items-center space-x-4">
                            <span class="text-[10px] font-bold text-yellow-100 uppercase">Total Lengkung</span>
                            <div class="flex items-baseline space-x-1">
                              <input v-model="form4.total_lengkung" readonly class="bg-transparent text-white font-black text-sm w-16 text-right outline-none" />
                              <span class="text-[10px] text-yellow-200 font-bold">M'SP</span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="mb-6">
                    <div class="bg-gray-100 px-3 py-1.5 rounded-md mb-3 border-l-4 border-pink-500">
                        <span class="text-[11px] font-black text-gray-700 uppercase">F. Data Operasi Mesin (Working)</span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-1">
                        <div v-for="(val, label) in {
                            'Start Engine': (report?.waktu_start_engine?.slice(0, 5) || '-') + ' WIB',
                            'Stop Engine': (report?.workresult?.waktu_stop_engine?.slice(0, 5) || '-') + ' WIB',
                            'Jam Traveling Awal': (report?.jam_traveling_awal) + ' WIB',
                            'Jam Traveling Akhir': (report?.workresult?.jam_traveling_akhir)  + ' WIB',
                            'Awal Jam Kerja Op': (report?.jam_kerja_awal?.slice(0, 5) || '-') + ' WIB',
                            'Akhir Jam Kerja Op': (report?.workresult?.jam_kerja_akhir?.slice(0, 5) || '-') + ' WIB',
                            'Jam Mesin Awal': (report?.jam_mesin_awal) + ' Hours',
                            'Jam Mesin Akhir': (report?.workresult?.jam_mesin_akhir) + ' Hours',
                            'Jam Generator Awal': (report?.jam_generator_awal) + ' Hours',
                            'Jam Generator Akhir': (report?.workresult?.jam_generator_akhir) + ' Hours',
                            'Counter Tamping Awal': report?.counter_tamping_awal,
                            'Counter Tamping Akhir': report?.workresult?.counter_tamping_akhir,
                            'Oddometer Awal': report?.oddometer_awal,
                            'Oddometer Akhir': report?.workresult?.oddometer_akhir,
                            'HSD Awal Kerja': (report?.hsd_awal_kerja || '-') + ' %',
                            'HSD Akhir': (report?.workresult?.hsd_akhir_kerja || '-') + ' %',
                        }" :key="label" class="flex items-center py-2 border-b border-gray-50">
                            <div class="w-40 text-[11px] font-bold text-gray-400 uppercase leading-none">{{ label }}</div>
                            <div class="text-gray-300 mr-3">:</div>
                            <div class="text-xs font-bold text-gray-700 capitalize">{{ val || '-' }}</div>
                        </div>
                    </div>
                  </div>

                  <section class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-100">
                        <h3 class="font-bold text-gray-800 flex items-center text-sm md:text-base">
                            <span class="bg-sky-500 text-white w-6 h-6 rounded-full flex-shrink-0 flex items-center justify-center text-xs mr-2">1</span>
                            Data Opname Resor Jalan Rel (Awal)
                        </h3>
                    </div>

                    <div class="p-3 md:p-4 space-y-3">
                        <div class="group p-3 md:p-4 border border-gray-200 rounded-xl hover:border-sky-400 hover:shadow-md transition-all duration-200 bg-white">
                            <div class="flex flex-row items-center justify-between gap-2">
                                <label class="font-semibold text-gray-700 text-[11px] md:text-sm flex-1">a. IP 2 (Lurusan)</label>
                                <div class="flex items-center space-x-3 flex-shrink-0">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" v-model="form7.ada" true-value="1" false-value="0" class="w-3.5 h-3.5 rounded border-gray-300 text-sky-600 focus:ring-sky-500">
                                        <span class="ml-1.5 text-[11px] md:text-sm whitespace-nowrap" :class="form7.ada === '1' ? 'text-sky-600 font-bold' : 'text-gray-500'">Ada</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" v-model="form7.tidak" true-value="1" false-value="0" class="w-3.5 h-3.5 rounded border-gray-300 text-red-600 focus:ring-red-500">
                                        <span class="ml-1.5 text-[11px] md:text-sm whitespace-nowrap" :class="form7.tidak === '1' ? 'text-red-600 font-bold' : 'text-gray-500'">Tidak ada</span>
                                    </label>
                                </div>
                            </div>
                            <div class="mt-3 pt-3 border-t border-gray-50">
                                <AttachmentInline :model="mglurusanawal ?? {}" type="MgLurusanAwal" redaction="Lampiran (IP 2 Lurusan)" :attachments="mglurusanawal_attachments" />
                            </div>
                        </div>

                        <div class="group p-3 md:p-4 border border-gray-200 rounded-xl hover:border-sky-400 transition-all bg-white">
                            <div class="flex flex-row items-center justify-between gap-2">
                                <label class="font-semibold text-gray-700 text-[11px] md:text-sm flex-1">b. IG 2 (Lengkungan)</label>
                                <div class="flex items-center space-x-3 flex-shrink-0">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" v-model="form8.ada" true-value="1" false-value="0" class="w-3.5 h-3.5 rounded border-gray-300 text-sky-600">
                                        <span class="ml-1.5 text-[11px] md:text-sm whitespace-nowrap" :class="form8.ada === '1' ? 'text-sky-600 font-bold' : 'text-gray-500'">Ada</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" v-model="form8.tidak" true-value="1" false-value="0" class="w-3.5 h-3.5 rounded border-gray-300 text-red-600">
                                        <span class="ml-1.5 text-[11px] md:text-sm whitespace-nowrap" :class="form8.tidak === '1' ? 'text-red-600 font-bold' : 'text-gray-500'">Tidak ada</span>
                                    </label>
                                </div>
                            </div>
                            <div class="mt-3 pt-3 border-t border-gray-50">
                                <AttachmentInline :model="mglengkunganawal ?? {}" type="MgLengkunganAwal" redaction="Lampiran (IG 2 Lengkungan)" :attachments="mglengkunganawal_attachments" />
                            </div>
                        </div>

                        <div class="group p-3 md:p-4 border border-gray-200 rounded-xl hover:border-sky-400 transition-all bg-white">
                            <div class="flex flex-row items-center justify-between gap-2">
                                <label class="font-semibold text-gray-700 text-[11px] md:text-sm flex-1">b. IG 3 (Wesel)</label>
                                <div class="flex items-center space-x-3 flex-shrink-0">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" v-model="form9.ada" true-value="1" false-value="0" class="w-3.5 h-3.5 rounded border-gray-300 text-sky-600">
                                        <span class="ml-1.5 text-[11px] md:text-sm whitespace-nowrap" :class="form9.ada === '1' ? 'text-sky-600 font-bold' : 'text-gray-500'">Ada</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" v-model="form9.tidak" true-value="1" false-value="0" class="w-3.5 h-3.5 rounded border-gray-300 text-red-600">
                                        <span class="ml-1.5 text-[11px] md:text-sm whitespace-nowrap" :class="form9.tidak === '1' ? 'text-red-600 font-bold' : 'text-gray-500'">Tidak ada</span>
                                    </label>
                                </div>
                            </div>
                            <div class="mt-3 pt-3 border-t border-gray-50">
                                <AttachmentInline :model="mgweselawal ?? {}" type="MgWeselAwal" redaction="Lampiran (IG 3 Wesel)" :attachments="mgweselawal_attachments" />
                            </div>
                        </div>
                    </div>
                  </section>

                  <section class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-100">
                        <h3 class="font-bold text-gray-800 flex items-center text-sm md:text-base">
                            <span class="bg-sky-500 text-white w-6 h-6 rounded-full flex-shrink-0 flex items-center justify-center text-xs mr-2">2</span>
                            Data Pemeriksaan Silang
                        </h3>
                    </div>
                    <div class="p-3 md:p-4 space-y-3">
                        <div class="group p-3 md:p-4 border border-gray-200 rounded-xl hover:border-sky-400 bg-white transition-all">
                            <div class="flex flex-row items-center justify-between gap-2">
                                <label class="font-semibold text-gray-700 text-[11px] md:text-sm flex-1">a. KPJR</label>
                                <div class="flex items-center space-x-3 flex-shrink-0">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" v-model="form10.ada" true-value="1" false-value="0" class="w-3.5 h-3.5 rounded border-gray-300 text-sky-600">
                                        <span class="ml-1.5 text-[11px] md:text-sm whitespace-nowrap" :class="form10.ada === '1' ? 'text-sky-600 font-bold' : 'text-gray-500'">Ada</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" v-model="form10.tidak" true-value="1" false-value="0" class="w-3.5 h-3.5 rounded border-gray-300 text-red-600">
                                        <span class="ml-1.5 text-[11px] md:text-sm whitespace-nowrap" :class="form10.tidak === '1' ? 'text-red-600 font-bold' : 'text-gray-500'">Tidak ada</span>
                                    </label>
                                </div>
                            </div>
                            <div class="mt-3 pt-3 border-t border-gray-50">
                                <AttachmentInline :model="pemeriksaansilangkpjr ?? {}" type="PemeriksaanSilangKpjr" redaction="Lampiran (Pemeriksaan Silang KPJR)" :attachments="pemeriksaansilangkpjr_attachments" />
                            </div>
                        </div>
                    </div>
                    <div class="p-3 md:p-4 space-y-3">
                        <div class="group p-3 md:p-4 border border-gray-200 rounded-xl hover:border-sky-400 bg-white transition-all">
                            <div class="flex flex-row items-center justify-between gap-2">
                                <label class="font-semibold text-gray-700 text-[11px] md:text-sm flex-1">b. Lahan</label>
                                <div class="flex items-center space-x-3 flex-shrink-0">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" v-model="form11.ada" true-value="1" false-value="0" class="w-3.5 h-3.5 rounded border-gray-300 text-sky-600">
                                        <span class="ml-1.5 text-[11px] md:text-sm whitespace-nowrap" :class="form11.ada === '1' ? 'text-sky-600 font-bold' : 'text-gray-500'">Ada</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" v-model="form11.tidak" true-value="1" false-value="0" class="w-3.5 h-3.5 rounded border-gray-300 text-red-600">
                                        <span class="ml-1.5 text-[11px] md:text-sm whitespace-nowrap" :class="form11.tidak === '1' ? 'text-red-600 font-bold' : 'text-gray-500'">Tidak ada</span>
                                    </label>
                                </div>
                            </div>
                            <div class="mt-3 pt-3 border-t border-gray-50">
                                <AttachmentInline :model="pemeriksaansilanglahan ?? {}" type="PemeriksaanSilangLahan" redaction="Lampiran (Pemeriksaan Silang Lahan)" :attachments="pemeriksaansilanglahan_attachments" />
                            </div>
                        </div>
                    </div>
                  </section>

                  <section class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-100">
                        <h3 class="font-bold text-gray-800 flex items-center text-sm md:text-base">
                            <span class="bg-sky-500 text-white w-6 h-6 rounded-full flex-shrink-0 flex items-center justify-center text-xs mr-2">3</span>
                            Data Opname Resor Jalan Rel (Akhir)
                        </h3>
                    </div>

                    <div class="p-3 md:p-4 space-y-3">
                      <div class="group p-3 md:p-4 border border-gray-200 rounded-xl hover:border-sky-400 hover:shadow-md transition-all duration-200 bg-white">
                          <div class="flex flex-row items-center justify-between gap-2">
                              <label class="font-semibold text-gray-700 text-[11px] md:text-sm flex-1">a. IP 2 (Lurusan)</label>
                              <div class="flex items-center space-x-3 flex-shrink-0">
                                  <label class="inline-flex items-center cursor-pointer">
                                      <input type="checkbox" v-model="form13.ada" true-value="1" false-value="0" class="w-3.5 h-3.5 rounded border-gray-300 text-sky-600 focus:ring-sky-500">
                                      <span class="ml-1.5 text-[11px] md:text-sm whitespace-nowrap" :class="form13.ada === '1' ? 'text-sky-600 font-bold' : 'text-gray-500'">Ada</span>
                                  </label>
                                  <label class="inline-flex items-center cursor-pointer">
                                      <input type="checkbox" v-model="form13.tidak" true-value="1" false-value="0" class="w-3.5 h-3.5 rounded border-gray-300 text-red-600 focus:ring-red-500">
                                      <span class="ml-1.5 text-[11px] md:text-sm whitespace-nowrap" :class="form13.tidak === '1' ? 'text-red-600 font-bold' : 'text-gray-500'">Tidak ada</span>
                                  </label>
                              </div>
                          </div>
                          <div class="mt-3 pt-3 border-t border-gray-50">
                              <AttachmentInline :model="mglurusanakhir ?? {}" type="MgLurusanAkhir" redaction="Lampiran (IP 2 Lurusan Akhir)" :attachments="mglurusanakhir" />
                          </div>
                      </div>

                      <div class="group p-3 md:p-4 border border-gray-200 rounded-xl hover:border-sky-400 transition-all bg-white">
                          <div class="flex flex-row items-center justify-between gap-2">
                              <label class="font-semibold text-gray-700 text-[11px] md:text-sm flex-1">b. IG 2 (Lengkungan)</label>
                              <div class="flex items-center space-x-3 flex-shrink-0">
                                  <label class="inline-flex items-center cursor-pointer">
                                      <input type="checkbox" v-model="form14.ada" true-value="1" false-value="0" class="w-3.5 h-3.5 rounded border-gray-300 text-sky-600">
                                      <span class="ml-1.5 text-[11px] md:text-sm whitespace-nowrap" :class="form14.ada === '1' ? 'text-sky-600 font-bold' : 'text-gray-500'">Ada</span>
                                  </label>
                                  <label class="inline-flex items-center cursor-pointer">
                                      <input type="checkbox" v-model="form14.tidak" true-value="1" false-value="0" class="w-3.5 h-3.5 rounded border-gray-300 text-red-600">
                                      <span class="ml-1.5 text-[11px] md:text-sm whitespace-nowrap" :class="form14.tidak === '1' ? 'text-red-600 font-bold' : 'text-gray-500'">Tidak ada</span>
                                  </label>
                              </div>
                          </div>
                          <div class="mt-3 pt-3 border-t border-gray-50">
                              <AttachmentInline :model="mglengkunganakhir ?? {}" type="MgLengkunganAkhir" redaction="Lampiran (IG 2 Lengkungan Akhir)" :attachments="mglengkunganakhir_attachments" />
                          </div>
                      </div>

                      <div class="group p-3 md:p-4 border border-gray-200 rounded-xl hover:border-sky-400 transition-all bg-white">
                          <div class="flex flex-row items-center justify-between gap-2">
                              <label class="font-semibold text-gray-700 text-[11px] md:text-sm flex-1">b. IG 3 (Wesel)</label>
                              <div class="flex items-center space-x-3 flex-shrink-0">
                                  <label class="inline-flex items-center cursor-pointer">
                                      <input type="checkbox" v-model="form15.ada" true-value="1" false-value="0" class="w-3.5 h-3.5 rounded border-gray-300 text-sky-600">
                                      <span class="ml-1.5 text-[11px] md:text-sm whitespace-nowrap" :class="form15.ada === '1' ? 'text-sky-600 font-bold' : 'text-gray-500'">Ada</span>
                                  </label>
                                  <label class="inline-flex items-center cursor-pointer">
                                      <input type="checkbox" v-model="form15.tidak" true-value="1" false-value="0" class="w-3.5 h-3.5 rounded border-gray-300 text-red-600">
                                      <span class="ml-1.5 text-[11px] md:text-sm whitespace-nowrap" :class="form15.tidak === '1' ? 'text-red-600 font-bold' : 'text-gray-500'">Tidak ada</span>
                                  </label>
                              </div>
                          </div>
                          <div class="mt-3 pt-3 border-t border-gray-50">
                              <AttachmentInline :model="mgweselakhir ?? {}" type="MgWeselanAkhir" redaction="Lampiran (IG 3 Wesel Akhir)" :attachments="mgweselakhir_attachments" />
                          </div>
                      </div>
                    </div>
                  </section>

                  <section class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-100">
                        <h3 class="font-bold text-gray-800 flex items-center text-sm md:text-base">
                            <span class="bg-sky-500 text-white w-6 h-6 rounded-full flex-shrink-0 flex items-center justify-center text-xs mr-2">4</span>
                            Data Pemeriksaan Silang Akhir
                        </h3>
                    </div>
                    <div class="p-3 md:p-4 space-y-3">
                        <div class="group p-3 md:p-4 border border-gray-200 rounded-xl hover:border-sky-400 bg-white transition-all">
                            <div class="flex flex-row items-center justify-between gap-2">
                                <label class="font-semibold text-gray-700 text-[11px] md:text-sm flex-1">a. KPJR</label>
                                <div class="flex items-center space-x-3 flex-shrink-0">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" v-model="form16.ada" true-value="1" false-value="0" class="w-3.5 h-3.5 rounded border-gray-300 text-sky-600">
                                        <span class="ml-1.5 text-[11px] md:text-sm whitespace-nowrap" :class="form16.ada === '1' ? 'text-sky-600 font-bold' : 'text-gray-500'">Ada</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" v-model="form16.tidak" true-value="1" false-value="0" class="w-3.5 h-3.5 rounded border-gray-300 text-red-600">
                                        <span class="ml-1.5 text-[11px] md:text-sm whitespace-nowrap" :class="form16.tidak === '1' ? 'text-red-600 font-bold' : 'text-gray-500'">Tidak ada</span>
                                    </label>
                                </div>
                            </div>
                            <div class="mt-3 pt-3 border-t border-gray-50">
                                <AttachmentInline :model="perekamanakhir ?? {}" type="PerekamanAkhir" redaction="Lampiran (Pemeriksaan Silang Akhir)" :attachments="perekamanakhir_attachments" />
                            </div>
                        </div>
                    </div>
                  </section>
                </div>

                <div class="space-y-2 mt-4 p-4 bg-gray-50 rounded-lg border border-gray-100">
                  <h4 class="text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest text-center">Status Persetujuan</h4>
                  <div v-if="report?.operator_at1" class="flex items-center gap-2 text-[11px] font-bold text-blue-700">
                      <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                      Op 1 disetujui: {{ formatDate(report?.operator_at1) }}
                  </div>
                  <div v-if="report?.operator_at2" class="flex items-center gap-2 text-[11px] font-bold text-blue-700">
                      <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                      Op 2 disetujui: {{ formatDate(report?.operator_at2) }}
                  </div>
                  <div v-if="report?.operator_at3" class="flex items-center gap-2 text-[11px] font-bold text-blue-700">
                      <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                      Op 3 disetujui: {{ formatDate(report?.operator_at3) }}
                  </div>
                  <div v-if="report?.kupt_at1" class="flex items-center gap-2 text-[11px] font-bold text-green-700 border-t border-green-100 pt-2 mt-2">
                      <span class="w-2 h-2 rounded-full bg-green-500"></span>
                      KUPT disetujui: {{ formatDate(report?.kupt_at1) }}
                  </div>
                </div>

                <div class="mt-8 border-t border-gray-100 pt-6">
                  <div class="flex flex-wrap justify-end gap-3">
                      
                    <template v-if="report.created_by_id == user.id && report.warmingup?.id">
                        <Button v-if="report.operator_by1 && !report.operator_at1" 
                            class="bg-blue-600 hover:bg-blue-800 text-white px-5 py-2 rounded shadow-lg text-[11px] font-bold transition-all active:scale-95" 
                            @click.prevent="approve(1)">
                            Konfirmasi Operator 1
                        </Button>
                        
                        <Button v-if="report.operator_by2 && report.operator_at1 && !report.operator_at2" 
                            class="bg-blue-600 hover:bg-blue-800 text-white px-5 py-2 rounded shadow-lg text-[11px] font-bold transition-all active:scale-95" 
                            @click.prevent="approve(2)">
                            Konfirmasi Operator 2
                        </Button>
                        
                        <Button v-if="report.operator_by3 && report.operator_at2 && !report.operator_at3" 
                            class="bg-blue-600 hover:bg-blue-800 text-white px-5 py-2 rounded shadow-lg text-[11px] font-bold transition-all active:scale-95" 
                            @click.prevent="approve(3)">
                            Konfirmasi Operator 3
                        </Button>
                    </template>

                    <Button v-if="hasRole(['admin', 'Kepala UPT Mekanik']) && !report.kupt_at1 && (
                            report.operator_at3 || 
                            (report.operator_at2 && (!report.operator_by3 || report.operator_by3 === '' || report.operator3?.name === '-'))
                        )"
                        class="bg-emerald-600 hover:bg-emerald-800 text-white px-6 py-2 rounded shadow-lg text-[11px] font-bold transition-all active:scale-95" 
                        @click.prevent="approveKUPT()">
                        Final Approve KUPT
                    </Button>
                  </div>
                </div>

            </div>

            <div v-if="currentSection === 'workresultok' && report?.mode === 'warmingup'" class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden" id="list-workresultok" role="tabpanel">
    
              <!-- <div class="bg-gradient-to-r from-gray-800 to-gray-700 p-4 flex justify-between items-center">
                  <h3 class="text-white text-sm font-bold uppercase tracking-wider">Ringkasan Laporan Warming Up</h3>
                  <span class="bg-blue-500 text-white text-[10px] px-2 py-1 rounded font-bold uppercase">{{ report?.nomor_sarana }}</span>
              </div> -->

              <div class="p-5">
                  <div class="mb-6">

                    <div class="bg-gray-100 px-3 py-1.5 rounded-md mb-3 border-l-4 border-gray-800">
                        <span class="text-[11px] font-black text-gray-700 uppercase">A. Informasi Umum</span>
                    </div>
                    
                      <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-1">
                        <div v-for="(val, label) in {
                            'Hari / Tanggal': formatDateDay(report?.date || '-'),
                            'Cuaca': report?.cuaca,
                            'Awal Jam Kerja': (report?.jam_kerja_awal?.slice(0, 5) || '-') + ' WIB',
                        }" :key="label" class="flex items-center py-2 border-b border-gray-100 group">
                            <div class="w-40 text-[11px] font-bold text-gray-400 uppercase tracking-tight">{{ label }}</div>
                            <div class="text-gray-300 mr-3">:</div>
                            <div class="text-xs font-semibold text-gray-800 group-hover:text-blue-600 transition-colors capitalize">
                                {{ val || '-' }}
                            </div>
                        </div>
                      </div>
                  </div>

                  <div class="mb-6">
                      <div class="bg-gray-100 px-3 py-1.5 rounded-md mb-3 border-l-4 border-green-600">
                          <span class="text-[11px] font-black text-gray-700 uppercase">B. Data Personel</span>
                      </div>
                      
                      <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-1">
                          <div v-for="(val, label) in {
                              'Operator 1': `[${report?.operator1?.username || ''}] ${report?.operator1?.name || '-'}`,
                              'Operator 2': `[${report?.operator2?.username || ''}] ${report?.operator2?.name || '-'}`,
                              ...(report?.operator_by3 && report?.operator3?.name && report?.operator3?.name !== '-' ? {'Operator 3': `[${report?.operator3?.username || ''}] ${report?.operator3?.name || '-'}`} : {}),
                              ...(report?.nipp && report?.nipp !== '' ? {'Pengawal 1': `[${report?.nipp}] ${report?.nama_pengawal || '-'}`} : {}),
                              ...(report?.nipp1 && report?.nipp1 !== '' ? {'Pengawal 2': `[${report?.nipp1}] ${report?.nama_pengawal1 || '-'}`} : {}),
                          }" :key="label" class="flex items-center py-2 border-b border-gray-50 group">
                              <div class="w-40 text-[11px] font-bold text-gray-400 uppercase">{{ label }}</div>
                              <div class="text-gray-300 mr-3">:</div>
                              <div class="text-xs font-bold text-gray-700 capitalize">{{ val.toLowerCase() }}</div>
                          </div>
                      </div>
                  </div>

                  <div class="mb-6">
                      <div class="bg-gray-100 px-3 py-1.5 rounded-md mb-3 border-l-4 border-yellow-600">
                          <span class="text-[11px] font-black text-gray-700 uppercase">C. Data Spesifikasi Mesin</span>
                      </div>
                      
                      <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-1">
                          <div v-for="(val, label) in {
                              'Klasifikasi': report?.klasifikasi,
                              'Type': report?.type,
                              'Merk': report?.jenis_kpjr,
                              'Nomor Sarana': report?.nomor_sarana,
                              'Nomor Mesin': report?.nomor_mesin,
                          }" :key="label" class="flex items-center py-2 border-b border-gray-50">
                              <div class="w-40 text-[11px] font-bold text-gray-400 uppercase leading-none">{{ label }}</div>
                              <div class="text-gray-300 mr-3">:</div>
                              <div class="text-xs font-bold text-gray-700 capitalize">{{ val || '-' }}</div>
                          </div>
                      </div>
                  </div>

                  <div class="mb-6">
                      <div class="bg-gray-100 px-3 py-1.5 rounded-md mb-3 border-l-4 border-blue-500">
                          <span class="text-[11px] font-black text-gray-700 uppercase">D. Data Operasi Mesin</span>
                      </div>
                      
                      <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-1">
                          <div v-for="(val, label) in {
                              'Start Engine': (report?.waktu_start_engine?.slice(0, 5) || '-') + ' WIB',
                              'Stop Engine': (report?.warmingup?.waktu_stop_engine?.slice(0, 5) || '-') + ' WIB',
                              'Jam Traveling Awal': (report?.jam_traveling_awal) + ' WIB',
                              // 'Jam Traveling Akhir': report?.warmingup?.jam_traveling_akhir,
                              'Awal Jam Kerja Op': (report?.jam_kerja_awal?.slice(0, 5) || '-') + ' WIB',
                              'Akhir Jam Kerja Op': (report?.warmingup?.jam_kerja_akhir?.slice(0, 5) || '-') + ' WIB',
                              'Jam Mesin Awal': (report?.jam_mesin_awal) + ' Hours',
                              'Jam Mesin Akhir': (report?.warmingup?.jam_mesin_akhir) + ' Hours',
                              'Jam Generator Awal': (report?.jam_generator_awal) + ' Hours',
                              'Jam Generator Akhir': (report?.warmingup?.jam_generator_akhir) + ' Hours',
                              'Counter Tamping Awal': report?.counter_tamping_awal,
                              'Counter Tamping Akhir': report?.warmingup?.counter_tamping_akhir,
                              'Oddometer Awal': report?.oddometer_awal,
                              'Oddometer Akhir': report?.warmingup?.oddometer_akhir,
                              'HSD Awal Kerja': (report?.hsd_awal_kerja || '-') + ' %',
                              'HSD Akhir Warming Up': (report?.warmingup?.hsd_akhir_kerja || '-') + ' %',
                              'Konsumsi HSD': (report?.warmingup?.konsumsi_hsd || '-') + ' Ltr',
                              'Satuan': (report?.warmingup?.satuan || '-')
                          }" :key="label" class="flex items-center py-2 border-b border-gray-50">
                              <div class="w-40 text-[11px] font-bold text-gray-400 uppercase leading-none">{{ label }}</div>
                              <div class="text-gray-300 mr-3">:</div>
                              <div class="text-xs font-bold text-gray-700 capitalize">{{ val || '-' }}</div>
                          </div>
                      </div>
                  </div>

                  <section class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-100">
                        <h3 class="font-bold text-gray-800 flex items-center text-sm md:text-base">
                            <span class="bg-sky-500 text-white w-6 h-6 rounded-full flex-shrink-0 flex items-center justify-center text-xs mr-2">1</span>
                            Data Opname Resor Jalan Rel (Awal)
                        </h3>
                    </div>

                    <div class="p-3 md:p-4 space-y-3">
                        <div class="group p-3 md:p-4 border border-gray-200 rounded-xl hover:border-sky-400 hover:shadow-md transition-all duration-200 bg-white">
                            <div class="flex flex-row items-center justify-between gap-2">
                                <label class="font-semibold text-gray-700 text-[11px] md:text-sm flex-1">a. IP 2 (Lurusan)</label>
                                <div class="flex items-center space-x-3 flex-shrink-0">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" v-model="form7.ada" true-value="1" false-value="0" class="w-3.5 h-3.5 rounded border-gray-300 text-sky-600 focus:ring-sky-500">
                                        <span class="ml-1.5 text-[11px] md:text-sm whitespace-nowrap" :class="form7.ada === '1' ? 'text-sky-600 font-bold' : 'text-gray-500'">Ada</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" v-model="form7.tidak" true-value="1" false-value="0" class="w-3.5 h-3.5 rounded border-gray-300 text-red-600 focus:ring-red-500">
                                        <span class="ml-1.5 text-[11px] md:text-sm whitespace-nowrap" :class="form7.tidak === '1' ? 'text-red-600 font-bold' : 'text-gray-500'">Tidak ada</span>
                                    </label>
                                </div>
                            </div>
                            <div class="mt-3 pt-3 border-t border-gray-50">
                                <AttachmentInline :model="mglurusanawal ?? {}" type="MgLurusanAwal" redaction="Lampiran (IP 2 Lurusan)" :attachments="mglurusanawal_attachments" />
                            </div>
                        </div>

                        <div class="group p-3 md:p-4 border border-gray-200 rounded-xl hover:border-sky-400 transition-all bg-white">
                            <div class="flex flex-row items-center justify-between gap-2">
                                <label class="font-semibold text-gray-700 text-[11px] md:text-sm flex-1">b. IG 2 (Lengkungan)</label>
                                <div class="flex items-center space-x-3 flex-shrink-0">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" v-model="form8.ada" true-value="1" false-value="0" class="w-3.5 h-3.5 rounded border-gray-300 text-sky-600">
                                        <span class="ml-1.5 text-[11px] md:text-sm whitespace-nowrap" :class="form8.ada === '1' ? 'text-sky-600 font-bold' : 'text-gray-500'">Ada</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" v-model="form8.tidak" true-value="1" false-value="0" class="w-3.5 h-3.5 rounded border-gray-300 text-red-600">
                                        <span class="ml-1.5 text-[11px] md:text-sm whitespace-nowrap" :class="form8.tidak === '1' ? 'text-red-600 font-bold' : 'text-gray-500'">Tidak ada</span>
                                    </label>
                                </div>
                            </div>
                            <div class="mt-3 pt-3 border-t border-gray-50">
                                <AttachmentInline :model="mglengkunganawal ?? {}" type="MgLengkunganAwal" redaction="Lampiran (IG 2 Lengkungan)" :attachments="mglengkunganawal_attachments" />
                            </div>
                        </div>

                        <div class="group p-3 md:p-4 border border-gray-200 rounded-xl hover:border-sky-400 transition-all bg-white">
                            <div class="flex flex-row items-center justify-between gap-2">
                                <label class="font-semibold text-gray-700 text-[11px] md:text-sm flex-1">b. IG 3 (Wesel)</label>
                                <div class="flex items-center space-x-3 flex-shrink-0">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" v-model="form9.ada" true-value="1" false-value="0" class="w-3.5 h-3.5 rounded border-gray-300 text-sky-600">
                                        <span class="ml-1.5 text-[11px] md:text-sm whitespace-nowrap" :class="form9.ada === '1' ? 'text-sky-600 font-bold' : 'text-gray-500'">Ada</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" v-model="form9.tidak" true-value="1" false-value="0" class="w-3.5 h-3.5 rounded border-gray-300 text-red-600">
                                        <span class="ml-1.5 text-[11px] md:text-sm whitespace-nowrap" :class="form9.tidak === '1' ? 'text-red-600 font-bold' : 'text-gray-500'">Tidak ada</span>
                                    </label>
                                </div>
                            </div>
                            <div class="mt-3 pt-3 border-t border-gray-50">
                                <AttachmentInline :model="mgweselawal ?? {}" type="MgWeselAwal" redaction="Lampiran (IG 3 Wesel)" :attachments="mgweselawal_attachments" />
                            </div>
                        </div>
                    </div>
                  </section>

                  <section class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-100">
                        <h3 class="font-bold text-gray-800 flex items-center text-sm md:text-base">
                            <span class="bg-sky-500 text-white w-6 h-6 rounded-full flex-shrink-0 flex items-center justify-center text-xs mr-2">2</span>
                            Data Pemeriksaan Silang
                        </h3>
                    </div>
                    <div class="p-3 md:p-4 space-y-3">
                        <div class="group p-3 md:p-4 border border-gray-200 rounded-xl hover:border-sky-400 bg-white transition-all">
                            <div class="flex flex-row items-center justify-between gap-2">
                                <label class="font-semibold text-gray-700 text-[11px] md:text-sm flex-1">a. KPJR</label>
                                <div class="flex items-center space-x-3 flex-shrink-0">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" v-model="form10.ada" true-value="1" false-value="0" class="w-3.5 h-3.5 rounded border-gray-300 text-sky-600">
                                        <span class="ml-1.5 text-[11px] md:text-sm whitespace-nowrap" :class="form10.ada === '1' ? 'text-sky-600 font-bold' : 'text-gray-500'">Ada</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" v-model="form10.tidak" true-value="1" false-value="0" class="w-3.5 h-3.5 rounded border-gray-300 text-red-600">
                                        <span class="ml-1.5 text-[11px] md:text-sm whitespace-nowrap" :class="form10.tidak === '1' ? 'text-red-600 font-bold' : 'text-gray-500'">Tidak ada</span>
                                    </label>
                                </div>
                            </div>
                            <div class="mt-3 pt-3 border-t border-gray-50">
                                <AttachmentInline :model="pemeriksaansilangkpjr ?? {}" type="PemeriksaanSilangKpjr" redaction="Lampiran (Pemeriksaan Silang KPJR)" :attachments="pemeriksaansilangkpjr_attachments" />
                            </div>
                        </div>
                    </div>
                    <div class="p-3 md:p-4 space-y-3">
                        <div class="group p-3 md:p-4 border border-gray-200 rounded-xl hover:border-sky-400 bg-white transition-all">
                            <div class="flex flex-row items-center justify-between gap-2">
                                <label class="font-semibold text-gray-700 text-[11px] md:text-sm flex-1">b. Lahan</label>
                                <div class="flex items-center space-x-3 flex-shrink-0">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" v-model="form11.ada" true-value="1" false-value="0" class="w-3.5 h-3.5 rounded border-gray-300 text-sky-600">
                                        <span class="ml-1.5 text-[11px] md:text-sm whitespace-nowrap" :class="form11.ada === '1' ? 'text-sky-600 font-bold' : 'text-gray-500'">Ada</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" v-model="form11.tidak" true-value="1" false-value="0" class="w-3.5 h-3.5 rounded border-gray-300 text-red-600">
                                        <span class="ml-1.5 text-[11px] md:text-sm whitespace-nowrap" :class="form11.tidak === '1' ? 'text-red-600 font-bold' : 'text-gray-500'">Tidak ada</span>
                                    </label>
                                </div>
                            </div>
                            <div class="mt-3 pt-3 border-t border-gray-50">
                                <AttachmentInline :model="pemeriksaansilanglahan ?? {}" type="PemeriksaanSilangLahan" redaction="Lampiran (Pemeriksaan Silang Lahan)" :attachments="pemeriksaansilanglahan_attachments" />
                            </div>
                        </div>
                    </div>
                  </section>

                  <div class="space-y-2 mt-4 p-4 bg-gray-50 rounded-lg border border-gray-100">
                      <h4 class="text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest text-center">Status Persetujuan</h4>
                      <div v-if="report?.operator_at1" class="flex items-center gap-2 text-[11px] font-bold text-blue-700">
                          <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                          Op 1 disetujui: {{ formatDate(report?.operator_at1) }}
                      </div>
                      <div v-if="report?.operator_at2" class="flex items-center gap-2 text-[11px] font-bold text-blue-700">
                          <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                          Op 2 disetujui: {{ formatDate(report?.operator_at2) }}
                      </div>
                      <div v-if="report?.operator_at3" class="flex items-center gap-2 text-[11px] font-bold text-blue-700">
                          <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                          Op 3 disetujui: {{ formatDate(report?.operator_at3) }}
                      </div>
                      <div v-if="report?.kupt_at1" class="flex items-center gap-2 text-[11px] font-bold text-green-700 border-t border-green-100 pt-2 mt-2">
                          <span class="w-2 h-2 rounded-full bg-green-500"></span>
                          KUPT disetujui: {{ formatDate(report?.kupt_at1) }}
                      </div>
                  </div>

                  <div class="mt-8 border-t border-gray-100 pt-6">
                      <div class="flex flex-wrap justify-end gap-3">
                          
                        <template v-if="report.created_by_id == user.id && report.warmingup?.id">
                            <Button v-if="report.operator_by1 && !report.operator_at1" 
                                class="bg-blue-600 hover:bg-blue-800 text-white px-5 py-2 rounded shadow-lg text-[11px] font-bold transition-all active:scale-95" 
                                @click.prevent="approve(1)">
                                Konfirmasi Operator 1
                            </Button>
                            
                            <Button v-if="report.operator_by2 && report.operator_at1 && !report.operator_at2" 
                                class="bg-blue-600 hover:bg-blue-800 text-white px-5 py-2 rounded shadow-lg text-[11px] font-bold transition-all active:scale-95" 
                                @click.prevent="approve(2)">
                                Konfirmasi Operator 2
                            </Button>
                            
                            <Button v-if="report.operator_by3 && report.operator_at2 && !report.operator_at3" 
                                class="bg-blue-600 hover:bg-blue-800 text-white px-5 py-2 rounded shadow-lg text-[11px] font-bold transition-all active:scale-95" 
                                @click.prevent="approve(3)">
                                Konfirmasi Operator 3
                            </Button>
                        </template>

                        <Button v-if="hasRole(['admin', 'Kepala UPT Mekanik']) && !report.kupt_at1 && (
                                report.operator_at3 || 
                                (report.operator_at2 && (!report.operator_by3 || report.operator_by3 === '' || report.operator3?.name === '-'))
                            )"
                            class="bg-emerald-600 hover:bg-emerald-800 text-white px-6 py-2 rounded shadow-lg text-[11px] font-bold transition-all active:scale-95" 
                            @click.prevent="approveKUPT()">
                            Final Approve KUPT
                        </Button>
                      </div>
                  </div>
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
