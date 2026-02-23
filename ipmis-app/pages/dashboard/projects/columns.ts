import type { ColumnDef } from '@tanstack/vue-table'
import { ArrowUpDown, ChevronDown } from 'lucide-vue-next'
import DataTableDropdown from '~/components/DataTableDropDown.vue'
import { h } from 'vue'
import Button from '~/components/ui/button/Button.vue'
import Badge from '~/components/ui/badge/Badge.vue'

export function getBadgeVariant(value: string, type: 'validation_assignment' | 'validation_status'): string {
    if (type === 'validation_assignment') {
        switch (value) {
            case 'Assigned':
                return 'success'
            case 'Unassigned':
                return 'destructive'
            default:
                return 'primary'
        }
    }

    if (type === 'validation_status') {
        switch (value) {
            case 'Validated':
                return 'success'
            case 'Unvalidated':
                return 'destructive'
            default:
                return 'primary'
        }
    }

    return 'primary'
}


// export const columns: ColumnDef<Project>[] = [
export const getProjectColumns = ({
    onEditProject,
    onOpenDetail,
    onRefresh,
}: {
    onEditProject: (project: Project) => void,
    onOpenDetail: (project: Project) => void,
    onRefresh: () => void,
}): ColumnDef<Project>[] => [
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
            accessorKey: 'cost',
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
                const raw = row.getValue('cost')

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
            accessorKey: 'project_type',
            header: ({ column }) => {
                return h(Button, {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                }, () => ['Project Type', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
            },
            cell: ({ row }) => h('div', { class: 'propercase' }, row.getValue('project_type')),
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
                }, () => ['Locations', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
            },
            cell: ({ row }) => h('div', { class: 'propercase' }, row.getValue('locations')),
            meta: {
                filterable: true,
            },
        },
        {
            accessorKey: 'other_requirements',
            header: 'Other Requirements',
            cell: ({ row }) => {
                const requirements = row.getValue('other_requirements') || []

                if (!Array.isArray(requirements) || !requirements.length)
                    return null

                const badges = requirements.map((r: any) => {
                    const label = r.requirement_type
                        .replace(/_/g, ' ')
                        .replace(/\b\w/g, c => c.toUpperCase())

                    const isIssued = r.date_issued && r.date_issued !== ''

                    const badgeClasses = [
                        'mr-1 mb-1 text-xs items-center justify-center text-center',
                        'border-radius: 10px 60px 40px font-homenaje',
                        isIssued
                            ? 'bg-badgesuccess text-badgesuccesstext'
                            : 'bg-badgedanger text-badgedangertext'
                    ]

                    return h(
                        Badge,
                        {
                            variant: 'secondary',
                            class: badgeClasses.join(' '),
                            style: 'border-radius: 10px 60px 30px;'
                        },
                        { default: () => label }
                    )
                })

                return h('div', { class: 'flex flex-wrap' }, badges)
            },
        },
        // {
        //     accessorKey: 'is_pamb',
        //     header: () => h('div', { class: 'text-left' }, 'Protected Area'),
        //     cell: ({ row }: any) => {
        //         const isPambValue = row.getValue('is_pamb')
        //         return isPambValue === 1
        //             ? h(Badge, { variant: 'secondary', class: 'propercase items-center justify-center text-center font-homenaje', style: 'border-radius: 10px 60px 30px;' }, 'Within PA')
        //             : null
        //     },
        // },
        {
            accessorKey: 'input_type',
            header: () => h('div', { class: 'text-left' }, 'Project Category'),
            cell: ({ row }: any) => {
                return h(Badge, { variant: 'default', class: 'uppercase font-homenaje', style: 'border-radius: 10px 60px 30px;' }, () => row.getValue('input_type'))
            }
        },
        // {
        //     accessorKey: 'funding_status',
        //     header: () => h('div', { class: 'text-left' }, 'Funding Status'),
        //     cell: ({ row }: any) => {
        //         return h(Badge, { variant: 'secondary', class: 'uppercase', style: 'border-radius: 10px 60px 30px;' }, () => row.getValue('funding_status'))
        //     }
        // },
        {
            accessorKey: 'validation_assignment',
            header: () => h('div', { class: 'text-left' }, 'Assignment'),
            meta: {
                filterable: false,
            },
            cell: ({ row }: any) => {
                const validation_assignment = row.getValue('validation_assignment')
                const badgeVariant = getBadgeVariant(validation_assignment, 'validation_assignment')
                return h(Badge, { variant: badgeVariant, class: 'text-xs px-2 py-0.5 rounded uppercase', style: 'border-radius: 10px 60px 30px;' }, () => validation_assignment)
            }
        },
        {
            accessorKey: 'validation_status',
            header: () => h('div', { class: 'text-left' }, 'Status'),
            meta: {
                filterable: false,
            },
            cell: ({ row }: any) => {
                const validation_status = row.getValue('validation_status')
                const badgeVariant = getBadgeVariant(validation_status, 'validation_status')
                return h(Badge, { variant: badgeVariant, class: 'text-xs px-2 py-0.5 rounded uppercase', style: 'border-radius: 10px 60px 30px;' }, () => validation_status)
            }
        },
        {
            id: 'actions',
            enableHiding: false,
            meta: {
                filterable: false,
            },
            cell: ({ row }) => {
                const project = row.original

                return h('div', { class: 'relative' }, h(DataTableDropdown, {
                    project,
                    onEditProject: () => onEditProject(project),
                    onOpenDetail: () => onOpenDetail(project),
                    onRefresh: () => {
                        window.dispatchEvent(new CustomEvent('datatable-refresh'))
                    }
                }))
            },
        },
    ]