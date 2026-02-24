import {
  CalendarIcon,
  ChartPieIcon,
  Cog6ToothIcon,
  DocumentDuplicateIcon,
  FolderIcon,
  HomeIcon,
  UsersIcon,
  XMarkIcon,
} from '@heroicons/vue/24/outline'
import { BookOpen, BookOpenCheck, BrickWall, DraftingCompass, FileText, Leaf, ListChecks, NotebookText, Settings } from 'lucide-vue-next'

export default function useSidebar() {
  const sidebarOpen = useState('sidebar-open', () => false)
  const auth = useAuth()
  const userRole = computed(() => auth.user?.value?.role)

  const rawNavigation = [
    { name: 'Dashboard', href: '/dashboard', icon: HomeIcon, current: false, },
    { name: 'Project Proposals', href: '/dashboard/projects', icon: FolderIcon, current: false, roles: ['1', 'ppdoDevChief', 'ppdoDevStaff'] },
    { name: 'Funded Projects', href: '/dashboard/funded_projects', icon: ListChecks, current: false, roles: ['1', 'ppdoMonitoringChief', 'ppdoMonitoringStaff'] },
    {
      name: 'Pre-Engineering',
      icon: DraftingCompass,
      current: false,
      children: [
        {
          name: 'Program of Work',
          href: '/dashboard/preengineerings/program_of_work',
          current: false,
          roles: ['1', 'peoPlanningChief', 'peoPlanningStaff'],
        },
        {
          name: 'Quality Control',
          href: '/dashboard/preengineerings/quality_control',
          current: false,
          roles: ['1', 'peoPlanningChief', 'peoPlanningStaff'],
        },
        {
          name: 'POW Review',
          href: '/dashboard/preengineerings/review',
          current: false,
          roles: ['1', 'peoPlanningChief', 'peoPlanningStaff'],
        },
      ]
    },
    { name: 'POW for Approval', href: '/dashboard/for_approval_pows', icon: BookOpen, current: false, roles: ['1', 'pgoStaff'] },
    { name: 'Approved POWs', href: '/dashboard/approved_pows', icon: BookOpenCheck, current: false, roles: ['1', 'bacInfraStaff'] },
    { name: 'Environmental Clearance', href: '/dashboard/environmental_clearances', icon: Leaf, current: false, roles: ['1', 'benroChief', 'benroStaff'] },
    { name: 'Implementation Schedule', href: '/dashboard/implementations', icon: BrickWall, current: false, roles: ['1', 'peoConstructionChief', 'peoConstructionStaff'] },
    { name: 'Archives', href: '/dashboard/archives', icon: FileText, current: false, roles: ['1', 'ppdoMonitoringChief', 'ppdoMonitoringStaff'] },
    {
      name: 'Reports',
      icon: NotebookText,
      current: false,
      children: [
        {
          name: 'PAMB',
          href: '/dashboard/reports/pamb/',
          current: false,
        },
        {
          name: 'Projects',
          href: '/dashboard/reports/projects',
          current: false
        }
      ],
      roles: ['1'],
    },
    {
      name: 'Settings',
      icon: Settings,
      current: false,
      children: [
        {
          name: 'Beneficiaries',
          href: '/dashboard/settings/beneficiaries',
          current: false,
          roles: ['1', 'ppdoDevChief', 'ppdoDevStaff']
        },
        {
          name: 'Fund Sources',
          href: '/dashboard/settings/fundsources',
          current: false,
          roles: ['1', 'ppdoMonitoringChief', 'ppdoMonitoringStaff']
        },
        {
          name: 'Project Types',
          href: '/dashboard/settings/projecttypes',
          current: false,
          roles: ['1', 'ppdoDevChief', 'ppdoDevStaff']
        },
        {
          name: 'Offices',
          href: '/dashboard/settings/offices',
          current: false,
          roles: ['1']
        },
        {
          name: 'Offices-Divisions',
          href: '/dashboard/settings/odsus',
          current: false,
          roles: ['1']
        },
        {
          name: 'Positions',
          href: '/dashboard/settings/positions',
          current: false,
        },
        {
          name: 'Employees',
          href: '/dashboard/settings/employees',
          current: false,
        },
        {
          name: 'Plantillas',
          href: '/dashboard/settings/plantillas',
          current: false,
        },
        {
          name: 'Specific Scope of Work',
          href: '/dashboard/settings/specific_scopes',
          current: false,
          roles: ['1', 'peoPlanningChief', 'peoPlanningStaff']
        },
        {
          name: 'Users',
          href: '/dashboard/settings/users',
          current: false,
        }
      ]
    }
  ]

  function hasAccess(item, role) {
    if (!item.roles) return true
    if (!role) return false
    return item.roles.includes(role)
  }

  const navigation = computed(() =>
    rawNavigation
      .filter(item => hasAccess(item, userRole.value))
      .map(item => ({
        ...item,
        children: item.children
          ? item.children.filter(child => hasAccess(child, userRole.value))
          : undefined,
      }))
      .filter(item => !item.children || item.children.length > 0)
  )



  return {
    sidebarOpen,
    navigation,
  }
}