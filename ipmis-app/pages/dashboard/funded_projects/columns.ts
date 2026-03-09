import type { ColumnDef } from '@tanstack/vue-table'
import { ArrowUpDown, ChevronDown } from 'lucide-vue-next'
import DataTableDropdown from '~/components/DataTableDropDown.vue'
import { h } from 'vue'
import Button from '~/components/ui/button/Button.vue'
import Badge from '~/components/ui/badge/Badge.vue'

export function getBadgeVariant(status: string): 'default' | 'destructive' | 'outline' | 'secondary' {
    switch (status) {
        case 'funded':
            return 'default'
        case 'pending':
            return 'outline'
        case 'rejected':
            return 'destructive'
        default:
            return 'secondary'
    }
}


// export const columns: ColumnDef<Project>[] = [
export const getProjectColumns = ({
    onEditProject,
    onOpenDetail,
    onSiteProblem,
    onPdcResult,
    onRefresh,
}: {
    onEditProject: (project: Project) => void,
    onOpenDetail: (project: Project) => void,
    onSiteProblem: (project: Project) => void,
    onPdcResult: (project: Project) => void,
    onRefresh: () => void,
}): ColumnDef<Project>[] => [
        {
            accessorKey: 'number',
            header: ({ column }) => {
                return h(Button, {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                }, () => ['Project Number', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
            },
            cell: ({ row }) => h('div', { class: 'propercase' }, row.getValue('number')),
            meta: {
                filterable: true,
            },
        },
        {
            accessorKey: 'reference_number',
            header: ({ column }) => {
                return h(Button, {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                }, () => ['Ref Number', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
            },
            cell: ({ row }) => h('div', { class: 'propercase' }, row.getValue('reference_number')),
            meta: {
                filterable: true,
            },
        },
        {
            accessorKey: 'title',
            header: ({ column }) => {
                return h(Button, {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                }, () => ['Title', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
            },
            cell: ({ row }) => h('div', { class: 'propercase' }, row.getValue('title')),
            meta: {
                filterable: true,
            },
        },
        {
            accessorKey: 'locations',
            header: ({ column }) => {
                return h(Button, {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                }, () => ['Location', h(ArrowUpDown, { class: 'ml-2 h-4 w-4 ' })])
            },
            cell: ({ row }) => h('div', { class: 'propercase' }, row.getValue('locations')),
            meta: {
                filterable: true,
            },
        },
        {
            accessorKey: 'fundsource',
            header: ({ column }) => {
                return h(Button, {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                }, () => ['Fundsource', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
            },
            cell: ({ row }) => h('div', { class: 'propercase' }, row.getValue('fundsource')),
            meta: {
                filterable: true,
            },
        },
        {
            accessorKey: 'approved_cost',
            header: ({ column }) => {
                return h(
                    Button,
                    {
                        variant: 'ghost',
                        onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                    },
                    () => ['Cost', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })]
                )
            },
            cell: ({ row }) => {
                const raw = row.getValue('approved_cost')

                const formatted = raw
                    ? new Intl.NumberFormat('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2,
                    }).format(Number(raw))
                    : ''

                return h('div', { class: 'propercase' }, formatted)
            },
            meta: {
                filterable: true,
            },
        },
        {
            accessorKey: 'latest_site_problem_type',
            header: () => h('div', { class: 'text-left' }, 'Remarks'),
            cell: ({ row }: any) => {
                const latest_site_problem_type = row.getValue('latest_site_problem_type')
                const latest_site_problem_pdc_result = row.original.latest_site_problem_pdc_result
                const latest_problem_type_status = latest_site_problem_pdc_result ? `${latest_site_problem_type} - ${latest_site_problem_pdc_result}` : latest_site_problem_type
                if (!latest_site_problem_type) return null
                return h(Badge,
                    {
                        class: 'bg-badgeinfo text-badgeinfotext text-xs px-2 py-0.5  rounded uppercase items-center justify-center text-center',
                        style: 'border-radius: 10px 30px 60px;',
                    }, () => latest_problem_type_status)
            }

        },
        // {
        //     accessorKey: 'site_problems',
        //     header: () => h('div', { class: 'text-left' }, 'Remarks'),
        //     cell: ({ row }: any) => {
        //         const site_problem = row.getValue('site_problems')

        //         if (!site_problem || (Array.isArray(site_problem) && site_problem.length === 0)) {
        //             return h('span', { class: 'text-gray-400 italic' }, '')
        //         }

        //         const text = Array.isArray(site_problem)
        //             ? site_problem.join(', ')
        //             : site_problem

        //         return h(
        //             Badge,
        //             {
        //                 variant: 'warning',
        //                 class: 'text-xs px-2 py-0.5  rounded uppercase items-center justify-center text-center',
        //                 style: 'border-radius: 10px 30px 60px;',
        //             },
        //             () => text
        //         )
        //     }

        // },
        // {
        //     accessorKey: 'status',
        //     header: () => h('div', { class: 'text-left' }, 'Status'),
        //     cell: ({ row }: any) => {
        //         const status = row.getValue('status')
        //         const badgeVariant = getBadgeVariant(status)
        //         return h(Badge, { variant: badgeVariant, class: 'uppercase' }, () => status)
        //     }
        // },
        {
            id: 'actions',
            enableHiding: false,
            cell: ({ row }) => {
                const project = row.original

                return h('div', { class: 'relative' }, h(DataTableDropdown, {
                    project,
                    onEditProject: () => onEditProject(project),
                    onOpenDetail: () => onOpenDetail(project),
                    onSiteProblem: () => onSiteProblem(project),
                    onPdcResult: () => onPdcResult(project),
                    onRefresh: () => {
                        window.dispatchEvent(new CustomEvent('datatable-refresh'))
                    }
                }))
            },
        },
    ]