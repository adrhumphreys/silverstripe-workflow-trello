import React, { useState, Fragment } from "react";
import PropTypes from "prop-types";
import {
    UncontrolledButtonDropdown,
    DropdownMenu,
    DropdownItem,
    DropdownToggle,
} from "reactstrap";
import classNames from "classnames";
import { inject } from "lib/Injector";
import { sendSelectedStep } from "../helper";

const WorkflowButton = ({
    recordId,
    recordType,
    selectedStepId,
    initialTrelloUrl = null,
    steps,
    route,
    WorkflowStep,
    WorkflowIcon,
}) => {
    const [selectedId, setSelectedId] = useState(selectedStepId);
    const [trelloUrl, setTrelloUrl] = useState(initialTrelloUrl);

    const selectedSteps = steps.filter((s) => s.id === selectedId);
    const selectedStep =
        Array.isArray(selectedSteps) && selectedSteps.length > 0
            ? selectedSteps[0]
            : null;
    const title = selectedStep ? `${selectedStep.title}` : "Workflow";

    const createOnClick = (stepId) => () => {
        setSelectedId(stepId);
        sendSelectedStep({
            route,
            stepId,
            recordId,
            recordType,
            setTrelloUrl,
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
                    {title}
                    <span className="sr-only">Update workflow</span>
                </DropdownToggle>
                <DropdownMenu>
                    {renderedSteps}
                    {trelloUrl ? (
                        <Fragment>
                            <DropdownItem divider />
                            <DropdownItem
                                className="workflow-widget__item workflow-widget__item--link"
                                target="_blank"
                                href={trelloUrl}
                            >
                                <WorkflowIcon />
                                Card in Trello
                            </DropdownItem>
                        </Fragment>
                    ) : null}
                </DropdownMenu>
            </UncontrolledButtonDropdown>
        </div>
    );
};

export { WorkflowButton as Component };

export default inject(["WorkflowStep", "WorkflowIcon"])(WorkflowButton);
