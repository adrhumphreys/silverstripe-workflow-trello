import React, { useState } from "react";
import PropTypes from "prop-types";
import {
    UncontrolledButtonDropdown,
    DropdownMenu,
    DropdownItem,
    DropdownToggle,
} from "reactstrap";
import { inject } from "lib/Injector";
import { sendSelectedStep } from "../helper";
import WorkflowIcon from "./WorkflowIcon";

const WorkflowStep = ({ id, title, onClick, selectedId }) => (
    <DropdownItem onClick={onClick}>
        {title}
        {selectedId === id ? " selected" : " not selected"}
    </DropdownItem>
);

const WorkflowButton = ({
    recordId,
    recordType,
    selectedStepId,
    steps,
    PopoverField,
    route,
}) => {
    const [selectedId, setSelectedId] = useState(selectedStepId);
    const [open, setOpen] = useState(false);
    const toggleCallback = () => setOpen(!open);

    const selectedSteps = steps.filter((s) => s.id === selectedId);
    const selectedStep =
        Array.isArray(selectedSteps) && selectedSteps.length > 0
            ? selectedSteps[0]
            : null;
    const title = selectedStep ? `${selectedStep.title}` : "Workflow";

    const popoverProps = {
        id: `workflow-widget-${recordType}-${recordId}`,
        buttonClassName: "font-icon-tree",
        title: title,
        data: {
            popoverTitle: "Edit trello workflow state",
            buttonTooltip: "Edit trello workflow state",
            placement: "top",
            trigger: "focus",
        },
    };

    const createOnClick = (stepId) => () => {
        setSelectedId(stepId);
        sendSelectedStep({
            route,
            stepId,
            recordId,
            recordType,
        });
    };

    const renderedSteps = steps.map((s) => (
        <WorkflowStep
            key={s.id}
            {...s}
            onClick={createOnClick(s.id)}
            selectedId={selectedId}
        />
    ));

    return (
        <div className="workflow-widget">
            <UncontrolledButtonDropdown>
                <DropdownToggle>
                    <WorkflowIcon />
                    <span className="sr-only">Update workflow</span>
                </DropdownToggle>
                <DropdownMenu>
                    <DropdownItem header>Header</DropdownItem>
                    {renderedSteps}
                    <DropdownItem divider />
                    <DropdownItem>
                        <WorkflowIcon />
                        Card in Trello
                    </DropdownItem>
                </DropdownMenu>
            </UncontrolledButtonDropdown>
        </div>
    );
};

export { WorkflowButton as Component };

export default inject()(WorkflowButton);
